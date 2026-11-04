/**
 * Estuaire Emploi — adaptateur dark pour les anciennes pages admin.
 *
 * Beaucoup de vues ont des couleurs codées en dur (style="background:#fff",
 * blocs <style> avec #667eea...). Ce script les convertit vers la palette dark :
 *   - fonds clairs        -> surfaces sombres
 *   - textes sombres      -> textes clairs
 *   - couleurs vives      -> 4 familles sobres (bleu, vert, ambre, rouge)
 * Les feuilles Tailwind et le thème (link) ne sont pas touchés.
 * Pour exclure une zone (ex. aperçu de CV « papier ») : data-no-dark sur un parent.
 */
(function () {
    'use strict';

    var FAMILIES = {
        primary: { solid: '#3f72d6', mid: '#4a7fe0', text: '#7ea6f4', rgb: '91, 141, 239' },
        success: { solid: '#2f8a63', mid: '#379870', text: '#6cc39b', rgb: '79, 178, 134' },
        warning: { solid: '#a5781f', mid: '#b58628', text: '#dcb25f', rgb: '214, 167, 74' },
        danger:  { solid: '#b94a4a', mid: '#c75656', text: '#ec8b8a', rgb: '226, 112, 111' }
    };

    var ctx = document.createElement('canvas').getContext('2d');
    var cache = {};

    function parse(str) {
        if (!str) return null;
        var s = String(str).trim().toLowerCase();
        if (!s || s.indexOf('var(') !== -1 || /^(transparent|inherit|initial|unset|currentcolor|none|auto)$/.test(s)) return null;
        if (s in cache) return cache[s];
        var out = null;
        ctx.fillStyle = '#010203';
        ctx.fillStyle = s;
        var a = ctx.fillStyle;
        ctx.fillStyle = '#030201';
        ctx.fillStyle = s;
        if (ctx.fillStyle === a) {
            var m;
            if (a[0] === '#') {
                out = { r: parseInt(a.substr(1, 2), 16), g: parseInt(a.substr(3, 2), 16), b: parseInt(a.substr(5, 2), 16), a: 1 };
            } else if ((m = a.match(/rgba?\(([^)]+)\)/))) {
                var p = m[1].split(',').map(parseFloat);
                out = { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
            }
        }
        cache[s] = out;
        return out;
    }

    function hsl(c) {
        var r = c.r / 255, g = c.g / 255, b = c.b / 255;
        var max = Math.max(r, g, b), min = Math.min(r, g, b);
        var l = (max + min) / 2, h = 0, s = 0, d = max - min;
        if (d) {
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            if (max === r) h = ((g - b) / d + (g < b ? 6 : 0));
            else if (max === g) h = (b - r) / d + 2;
            else h = (r - g) / d + 4;
            h *= 60;
        }
        return { h: h, s: s, l: l };
    }

    function family(h) {
        if (h < 15 || h >= 345) return FAMILIES.danger;
        if (h < 65) return FAMILIES.warning;
        if (h < 170) return FAMILIES.success;
        return FAMILIES.primary;
    }

    function isNeutral(x) { return x.s < 0.14 || x.l > 0.97 || x.l < 0.04; }

    function mapBg(str) {
        var c = parse(str);
        if (!c || c.a === 0) return null;
        var x = hsl(c);
        if (isNeutral(x)) {
            if (c.a < 1) return null;              // voiles blancs/noirs translucides : on garde
            if (x.l < 0.22) return null;           // déjà sombre
            if (x.l >= 0.95) return 'var(--surface)';
            if (x.l >= 0.9) return 'var(--surface-2)';
            if (x.l >= 0.75) return 'var(--surface-3)';
            return 'var(--border-strong)';
        }
        var f = family(x.h);
        if (c.a < 1) return 'rgba(' + f.rgb + ', ' + Math.min(c.a, 0.18) + ')';
        if (x.l >= 0.8) return 'rgba(' + f.rgb + ', 0.12)';
        if (x.l < 0.2) return null;
        return f.solid;
    }

    function mapText(str) {
        var c = parse(str);
        if (!c) return null;
        var x = hsl(c);
        if (isNeutral(x)) {
            if (x.l < 0.2) return 'var(--text)';
            if (x.l < 0.38) return 'var(--text-2)';
            if (x.l < 0.7) return 'var(--muted)';
            return null;                            // texte déjà clair
        }
        if (x.l > 0.85) return null;
        return family(x.h).text;
    }

    function mapBorder(str) {
        var c = parse(str);
        if (!c || c.a === 0) return null;
        var x = hsl(c);
        if (isNeutral(x)) {
            if (c.a < 1 || x.l < 0.22) return null;
            return x.l > 0.6 ? 'var(--border)' : 'var(--border-strong)';
        }
        return 'rgba(' + family(x.h).rgb + ', 0.45)';
    }

    var COLOR_RE = /rgba?\([^)]*\)|#[0-9a-f]{3,8}\b|\b(white|black)\b/gi;

    function mapImage(str) {
        if (!str || str.indexOf('gradient') === -1) return null;
        var changed = false;
        // Dégradés = décor (cartes "hero", bandeaux) : on les rend en fond sombre teinté
        var out = str.replace(COLOR_RE, function (m) {
            var c = parse(m), v = null;
            if (c && c.a > 0) {
                var x = hsl(c);
                if (!isNeutral(x)) v = 'rgba(' + family(x.h).rgb + ', ' + (x.l >= 0.8 ? 0.06 : 0.16) + ')';
                else if (c.a === 1 && x.l >= 0.22) v = mapBg(m);
            }
            if (v) { changed = true; return v; }
            return m;
        });
        return changed ? out : null;
    }

    function mapCustomProp(str) {
        var c = parse(str);
        if (!c) return null;
        var x = hsl(c);
        if (isNeutral(x)) return x.l > 0.6 ? mapBg(str) : mapText(str);
        return x.l >= 0.8 ? mapBg(str) : family(x.h).mid;
    }

    function mapFor(prop) {
        if (prop.indexOf('--') === 0) return mapCustomProp;
        if (prop === 'color') return mapText;
        if (prop === 'background-color') return mapBg;
        if (prop === 'background-image') return mapImage;
        if (/^border(-(top|right|bottom|left))?-color$/.test(prop) || prop === 'outline-color') return mapBorder;
        return null;
    }

    var SEM_RE = /var\(--(primary|info|success|danger|warning)\)/g;
    function solidVar(str) {
        return str.replace(SEM_RE, function (m, k) { return 'var(--' + (k === 'info' ? 'primary' : k) + '-solid)'; });
    }

    function adaptDeclarations(style, isRootRule) {
        // Raccourci "background: var(--info)" : les longhands sont vides, on traite le raccourci
        var shorthand = style.getPropertyValue('background');
        if (shorthand && SEM_RE.test(shorthand)) {
            SEM_RE.lastIndex = 0;
            style.setProperty('background', solidVar(shorthand), style.getPropertyPriority('background'));
        }
        SEM_RE.lastIndex = 0;
        var props = [];
        for (var i = 0; i < style.length; i++) props.push(style[i]);
        props.forEach(function (prop) {
            if (isRootRule && (prop === 'background-color' || prop === 'background-image')) {
                style.removeProperty(prop);          // body/html : on garde le fond du thème
                return;
            }
            var raw = style.getPropertyValue(prop);
            // var(--info) & co. sont des couleurs de texte : en fond, on prend la version pleine
            if ((prop === 'background-color' || prop === 'background-image') && /var\(--(primary|info|success|danger|warning)\)/.test(raw)) {
                style.setProperty(prop, solidVar(raw), style.getPropertyPriority(prop));
                return;
            }
            var fn = mapFor(prop);
            if (!fn) return;
            var v = fn(raw);
            if (v) style.setProperty(prop, v, style.getPropertyPriority(prop));
        });
    }

    var done = new WeakSet();

    function adaptElement(el) {
        if (done.has(el) || !el.style || !el.style.length) return;
        done.add(el);
        if (el.closest && el.closest('[data-no-dark]')) return;
        if (el instanceof SVGElement) return;
        if (/^(SPAN|SMALL|LABEL|B|STRONG|EM|MARK|SUP)$/.test(el.tagName) && softPill(el)) return;
        if (/^(DIV|SECTION|HEADER|ARTICLE|ASIDE)$/.test(el.tagName) && tintBlock(el)) return;
        adaptDeclarations(el.style, false);
    }

    // Petites pastilles inline (span coloré + texte blanc) : teinte douce + texte coloré
    function softPill(el) {
        var st = el.style;
        var sh = st.getPropertyValue('background') || '';
        var m = sh.match(/var\(--(primary|info|success|danger|warning)\)/);
        var fam = null, soft = null, text = null;
        if (m) {
            var k = m[1] === 'info' ? 'primary' : m[1];
            soft = 'var(--' + k + '-soft)'; text = 'var(--' + k + ')';
        } else {
            var c = parse(st.backgroundColor);
            if (!c || c.a < 1) return false;
            var x = hsl(c);
            if (isNeutral(x) || x.l >= 0.8 || x.l < 0.2) return false;
            fam = family(x.h);
            soft = 'rgba(' + fam.rgb + ', 0.14)'; text = fam.text;
        }
        st.setProperty('background', soft);
        st.setProperty('color', text);
        if (st.borderColor) st.setProperty('border-color', 'transparent');
        if (!st.whiteSpace) st.setProperty('white-space', 'nowrap');
        if (!st.borderRadius || parseFloat(st.borderRadius) < 6) st.setProperty('border-radius', '999px');
        return true;
    }

    // Grands blocs en aplat coloré (en-têtes de cartes "hero") : fond sombre teinté + liseré
    function tintBlock(el) {
        var st = el.style;
        var c = parse(st.backgroundColor);
        if (!c || c.a < 1) return false;
        var x = hsl(c);
        if (isNeutral(x) || x.l >= 0.8 || x.l < 0.2) return false;
        var r = el.getBoundingClientRect();
        if (r.width * r.height < 20000) return false;
        var f = family(x.h);
        st.setProperty('background', 'rgba(' + f.rgb + ', 0.12)');
        st.setProperty('box-shadow', 'inset 0 0 0 1px rgba(' + f.rgb + ', 0.22)');
        var t = parse(st.color);
        if (t && hsl(t).l < 0.5) st.setProperty('color', 'var(--text)');
        return true;
    }

    function adaptTree(root) {
        if (root.nodeType !== 1) return;
        if (root.hasAttribute('style')) adaptElement(root);
        var list = root.querySelectorAll('[style]');
        for (var i = 0; i < list.length; i++) adaptElement(list[i]);
    }

    function walkRules(rules) {
        for (var i = 0; i < rules.length; i++) {
            var r = rules[i];
            if (r.style && r.selectorText !== undefined) {
                if (/\[data-no-dark\]/.test(r.selectorText)) continue;
                adaptDeclarations(r.style, /(^|[\s,])(html|body)(?![\w-])/.test(r.selectorText));
            } else if (r.cssRules) {
                walkRules(r.cssRules);
            }
        }
    }

    function adaptStyleSheets() {
        var sheets = document.styleSheets;
        for (var i = 0; i < sheets.length; i++) {
            var sheet = sheets[i], node = sheet.ownerNode;
            if (done.has(sheet) || !node || node.tagName !== 'STYLE') continue;
            done.add(sheet);
            if (node.hasAttribute('data-theme-skip')) continue;
            var txt = node.textContent || '';
            if (txt.indexOf('tailwindcss') !== -1 || txt.indexOf('--tw-') !== -1) continue;
            try { walkRules(sheet.cssRules); } catch (e) { /* feuille inaccessible */ }
        }
    }

    // Tableaux -> cartes sur mobile : on recopie l'en-tête de colonne dans chaque cellule
    function stackTables(root) {
        var tables = (root.querySelectorAll ? root : document).querySelectorAll('main table:not(.is-stackable):not([data-no-stack])');
        for (var i = 0; i < tables.length; i++) {
            var t = tables[i];
            if (t.querySelector('table') || t.closest('[data-no-stack]')) continue;
            var head = t.tHead && t.tHead.rows[0];
            if (!head || head.cells.length < 3 || !t.tBodies.length) continue;
            var labels = [], ok = true;
            for (var c = 0; c < head.cells.length; c++) {
                if (head.cells[c].colSpan > 1) { ok = false; break; }
                labels.push(head.cells[c].querySelector('input[type="checkbox"]') ? '' : head.cells[c].textContent.replace(/\s+/g, ' ').trim());
            }
            if (!ok) continue;
            var rows = t.tBodies[0].rows;
            for (var r = 0; r < rows.length; r++) {
                var cells = rows[r].cells;
                if (cells.length === 1 && cells[0].colSpan > 1) continue;      // ligne "aucun résultat"
                if (cells.length !== labels.length) { ok = false; break; }
                for (var k = 0; k < cells.length; k++) {
                    if (!cells[k].hasAttribute('data-label')) cells[k].setAttribute('data-label', labels[k]);
                }
            }
            if (ok) t.classList.add('is-stackable');
        }
    }

    // Couleurs des graphiques Chart.js : même palette sobre que le reste
    function mapChartColor(v) {
        if (typeof v !== 'string') return v;
        var c = parse(v);
        if (!c) return v;
        var x = hsl(c);
        if (isNeutral(x)) {
            if (x.l > 0.8) return c.a < 1 ? 'rgba(255, 255, 255, 0.06)' : '#3a4352';
            if (x.l > 0.3) return '#5a6477';
            return v;
        }
        var f = family(x.h);
        return c.a < 1 ? 'rgba(' + f.rgb + ', ' + Math.max(c.a, 0.25) + ')' : f.mid;
    }
    function mapChartValue(v) { return Array.isArray(v) ? v.map(mapChartColor) : mapChartColor(v); }
    function mapAxisColor(v) {
        if (typeof v !== 'string') return v;
        var c = parse(v);
        if (!c) return v;
        var x = hsl(c);
        return x.l > 0.6 ? 'rgba(255, 255, 255, 0.06)' : '#8a94a6';
    }

    var chartPluginRegistered = false;
    function chartDefaults() {
        if (!window.Chart || !window.Chart.defaults) return;
        if (!chartPluginRegistered && typeof window.Chart.register === 'function') {
            chartPluginRegistered = true;
            window.Chart.register({
                id: 'adminDarkTheme',
                beforeInit: function (chart) {
                    var cfg = chart.config || {};
                    var data = cfg.data || (chart.data);
                    (data && data.datasets || []).forEach(function (ds) {
                        ['backgroundColor', 'borderColor', 'pointBackgroundColor', 'pointBorderColor', 'hoverBackgroundColor', 'hoverBorderColor'].forEach(function (k) {
                            if (ds[k] !== undefined) ds[k] = mapChartValue(ds[k]);
                        });
                    });
                    var opts = cfg.options || chart.options || {};
                    var scales = opts.scales || {};
                    Object.keys(scales).forEach(function (key) {
                        var s = scales[key] || {};
                        if (s.grid && s.grid.color) s.grid.color = mapAxisColor(s.grid.color);
                        if (s.ticks && s.ticks.color) s.ticks.color = '#8a94a6';
                    });
                    var legend = opts.plugins && opts.plugins.legend;
                    if (legend && legend.labels && legend.labels.color) legend.labels.color = '#8a94a6';
                    var tip = opts.plugins && opts.plugins.tooltip;
                    if (tip && tip.backgroundColor) tip.backgroundColor = '#1c222d';
                }
            });
        }
        var d = window.Chart.defaults;
        d.color = '#8a94a6';
        d.borderColor = 'rgba(255, 255, 255, 0.06)';
        d.font.family = "Inter, ui-sans-serif, system-ui, sans-serif";
        if (d.plugins && d.plugins.tooltip) {
            d.plugins.tooltip.backgroundColor = '#1c222d';
            d.plugins.tooltip.borderColor = '#2f3846';
            d.plugins.tooltip.borderWidth = 1;
            d.plugins.tooltip.titleColor = '#e4e8ef';
            d.plugins.tooltip.bodyColor = '#b9c1cd';
        }
    }

    function run() {
        adaptStyleSheets();
        adaptTree(document.body);
        stackTables(document);
        chartDefaults();
    }

    run();
    document.addEventListener('DOMContentLoaded', run);

    // Contenu ajouté dynamiquement (Alpine, fetch, modales...)
    new MutationObserver(function (muts) {
        var sheetAdded = false;
        muts.forEach(function (m) {
            m.addedNodes.forEach(function (n) {
                if (n.nodeType !== 1) return;
                if (n.tagName === 'STYLE') sheetAdded = true;
                adaptTree(n);
                if (n.tagName === 'TABLE' || n.tagName === 'TR' || (n.querySelector && n.querySelector('table'))) stackTables(document);
            });
        });
        if (sheetAdded) adaptStyleSheets();
    }).observe(document.documentElement, { childList: true, subtree: true });

    window.AdminTheme = { adapt: adaptTree, colors: FAMILIES };
})();
