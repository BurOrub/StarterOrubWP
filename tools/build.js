// Build: divide CSS/starter.css per tipo di pagina, minifica CSS e JS. Uso: npm run build
const fs = require('fs');
const path = require('path');
const postcss = require('postcss');
const { transform } = require('lightningcss');
const esbuild = require('esbuild');

const root = path.join(__dirname, '..');
const out = path.join(root, 'CSS/dist');
fs.mkdirSync(out, { recursive: true });

// Un bucket si carica solo sulle pagine che ne hanno bisogno (vedi starter_css_buckets() in functions.php).
// Aggiungine altri qui via via che il progetto cresce (nuovi blocchi, sezioni, filtri...).
const rules = [
	['blog', /^(blog-|sidebar|widget|tagcloud|trust-row|contact-form)/],
	['blocks', /^(content-block|block-wrap|panel|section-head|eyebrow|text-balance|pill-button|round-button|icon-circle|breadcrumb|split|checklist|list-block|reviews|map-block|page-header|mission-vision|block-placeholder|hero-slider|slider-block|hero-video|hero-split|hero-media|values-strip|latest-posts|featured-products|insta-grid|team-grid|contacts-block|section-foot)/],
	['portfolio', /^project-/],
];

const bucketOf = (selector) => {
	const found = new Set();
	for (const [, cls] of selector.matchAll(/\.([\w-]+)/g)) {
		const hit = rules.find(([, re]) => re.test(cls));
		if (hit) found.add(hit[0]);
	}
	return found.size === 1 ? [...found][0] : 'base';
};

const ruleBucket = (rule) => {
	const set = new Set(rule.selectors.map(bucketOf));
	return set.size === 1 ? [...set][0] : 'base';
};

const buckets = {};
const push = (name, css) => ((buckets[name] = buckets[name] || []).push(css));

const source = fs.readFileSync(path.join(root, 'CSS/preset-starter.css'), 'utf8');
push('base', source);
const root_ = postcss.parse(fs.readFileSync(path.join(root, 'CSS/starter.css'), 'utf8'));

root_.each((node) => {
	if (node.type === 'rule') {
		push(ruleBucket(node), node.toString());
	} else if (node.type === 'atrule' && ['media', 'supports'].includes(node.name)) {
		const inner = {};
		node.each((child) => {
			const b = child.type === 'rule' ? ruleBucket(child) : 'base';
			(inner[b] = inner[b] || []).push(child.toString());
		});
		for (const [b, list] of Object.entries(inner)) push(b, `@${node.name} ${node.params} {\n${list.join('\n')}\n}`);
	} else if (node.type === 'atrule' && node.name === 'keyframes') {
		push('base', node.toString());
	} else if (node.type !== 'comment') {
		push('base', node.toString());
	}
});

// File CSS interi, ognuno un bucket a sé (non si divide per classi): "shop" = card prodotto, filtri, scheda, carrello (solo pagine Woo).
for (const name of ['shop', 'filters', 'immobili']) {
	const file = path.join(root, `CSS/${name}.css`);
	if (fs.existsSync(file)) push(name, fs.readFileSync(file, 'utf8'));
}

const manifest = {};
for (const [name, list] of Object.entries(buckets)) {
	const { code } = transform({
		filename: `${name}.css`,
		code: Buffer.from(list.join('\n')),
		minify: true,
		targets: { chrome: 100 << 16, safari: 15 << 16, firefox: 100 << 16 },
	});
	fs.writeFileSync(path.join(out, `${name}.min.css`), code);
	manifest[name] = code.length;
}
fs.writeFileSync(path.join(out, 'manifest.json'), JSON.stringify(manifest));

esbuild.buildSync({
	entryPoints: [path.join(root, 'JS/scripts.js')],
	outfile: path.join(root, 'JS/dist/scripts.min.js'),
	minify: true,
	target: 'es2019',
});

// JS dei moduli (file singoli, caricati solo dove servono).
for (const name of ['immobili']) {
	const file = path.join(root, `JS/${name}.js`);
	if (fs.existsSync(file)) {
		esbuild.buildSync({ entryPoints: [file], outfile: path.join(root, `JS/dist/${name}.min.js`), minify: true, target: 'es2019' });
	}
}

console.log('CSS (byte):', manifest, '| JS:', fs.statSync(path.join(root, 'JS/dist/scripts.min.js')).size);
