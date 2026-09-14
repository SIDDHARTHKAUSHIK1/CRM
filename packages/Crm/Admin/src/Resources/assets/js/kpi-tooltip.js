/**
 * KPI card — truncated value/label hover & focus preview tooltip.
 * Only displays when the target element's text is genuinely truncated (scrollWidth > clientWidth).
 */
export default function initKpiTruncationTooltips(root = document) {
    if (typeof document === 'undefined') return;

    let tooltipEl = document.querySelector('.kpi-tooltip');

    if (!tooltipEl) {
        tooltipEl = document.createElement('div');
        tooltipEl.className = 'kpi-tooltip';
        tooltipEl.setAttribute('role', 'tooltip');
        tooltipEl.setAttribute('aria-hidden', 'true');
        document.body.appendChild(tooltipEl);
    }

    let activeEl = null;

    function isTruncated(el) {
        if (!el) return false;
        return el.scrollWidth > el.clientWidth + 1;
    }

    function position(el) {
        if (!el || !tooltipEl) return;
        const rect = el.getBoundingClientRect();
        const tipRect = tooltipEl.getBoundingClientRect();
        
        let top = rect.top - tipRect.height - 8;
        let flipped = false;

        if (top < 8) {
            top = rect.bottom + 8;
            flipped = true;
        }

        const left = Math.min(
            Math.max(rect.left + rect.width / 2 - tipRect.width / 2, 8),
            window.innerWidth - tipRect.width - 8
        );

        tooltipEl.style.top = `${top}px`;
        tooltipEl.style.left = `${left}px`;
        tooltipEl.classList.toggle('kpi-tooltip--flipped', flipped);
    }

    function show(el) {
        if (!isTruncated(el)) {
            hide();
            return;
        }

        activeEl = el;
        const text = el.dataset.fullText || el.textContent.trim();
        if (!text) {
            hide();
            return;
        }

        tooltipEl.textContent = text;
        tooltipEl.setAttribute('aria-hidden', 'false');
        tooltipEl.classList.add('kpi-tooltip--visible');
        position(el);
    }

    function hide() {
        activeEl = null;
        if (tooltipEl) {
            tooltipEl.setAttribute('aria-hidden', 'true');
            tooltipEl.classList.remove('kpi-tooltip--visible');
        }
    }

    const resizeObserver = new ResizeObserver((entries) => {
        for (const entry of entries) {
            if (activeEl && entry.target === activeEl) {
                if (!isTruncated(activeEl)) {
                    hide();
                } else {
                    position(activeEl);
                }
            }
        }
    });

    function bindElement(el) {
        if (!el || el.dataset.tooltipBound) return;
        el.dataset.tooltipBound = '1';

        el.addEventListener('mouseenter', () => show(el));
        el.addEventListener('mouseleave', hide);
        el.addEventListener('focus', () => show(el));
        el.addEventListener('blur', hide);

        resizeObserver.observe(el);
    }

    function scan() {
        const elements = root.querySelectorAll ? root.querySelectorAll('[data-truncate-tooltip]') : document.querySelectorAll('[data-truncate-tooltip]');
        elements.forEach(bindElement);
    }

    scan();

    // Observe DOM mutations to catch elements rendered asynchronously by Vue components
    if (typeof MutationObserver !== 'undefined') {
        const mutationObserver = new MutationObserver(() => {
            scan();
        });

        const targetNode = root.body || document.body || root;
        if (targetNode) {
            mutationObserver.observe(targetNode, {
                childList: true,
                subtree: true
            });
        }
    }

    // Global event listeners for viewport adjustments
    window.addEventListener('resize', () => {
        if (activeEl && tooltipEl && tooltipEl.classList.contains('kpi-tooltip--visible')) {
            if (!isTruncated(activeEl)) {
                hide();
            } else {
                position(activeEl);
            }
        }
    }, { passive: true });

    window.addEventListener('scroll', () => {
        if (activeEl && tooltipEl && tooltipEl.classList.contains('kpi-tooltip--visible')) {
            position(activeEl);
        }
    }, { passive: true });
}
