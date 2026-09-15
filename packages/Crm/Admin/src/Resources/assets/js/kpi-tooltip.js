/**
 * KPI card — truncated value/label hover & focus preview tooltip.
 * Displays a clean, high-contrast preview whenever a KPI card's value or label is squeezed/truncated.
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
        // Direct overflow check
        if (el.scrollWidth > el.clientWidth + 0.5) return true;
        if (el.scrollHeight > el.clientHeight + 0.5) return true;

        // Container overflow check: if hovering a card, check its value or text children
        if (el.querySelectorAll) {
            const candidates = el.querySelectorAll('[data-full-text], .auto-fit-text, .truncate, [data-truncate-tooltip]');
            for (const child of candidates) {
                if (child.scrollWidth > child.clientWidth + 0.5) {
                    return true;
                }
            }
        }

        return false;
    }

    function getTargetAndText(el) {
        if (!el) return { target: null, text: '' };

        // 1. If el itself is truncated
        if (el.scrollWidth > el.clientWidth + 0.5) {
            return {
                target: el,
                text: el.dataset.fullText || el.textContent.trim()
            };
        }

        // 2. If el has data-full-text explicitly and its text is truncated or squeezed
        if (el.dataset.fullText && (el.scrollWidth > el.clientWidth + 0.5 || el.classList.contains('truncate'))) {
            return {
                target: el,
                text: el.dataset.fullText
            };
        }

        // 3. If container has truncated child (e.g. big KPI value or label)
        if (el.querySelectorAll) {
            const candidates = el.querySelectorAll('[data-full-text], .auto-fit-text, .truncate, [data-truncate-tooltip]');
            for (const child of candidates) {
                if (child.scrollWidth > child.clientWidth + 0.5) {
                    return {
                        target: child,
                        text: child.dataset.fullText || child.textContent.trim()
                    };
                }
            }
        }

        if (el.dataset.fullText) {
            return { target: el, text: el.dataset.fullText };
        }

        return { target: el, text: el.textContent.trim() };
    }

    function position(target) {
        if (!target || !tooltipEl) return;
        const rect = target.getBoundingClientRect();
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

        const { target, text } = getTargetAndText(el);
        if (!text) {
            hide();
            return;
        }

        activeEl = el;
        tooltipEl.textContent = text;
        tooltipEl.setAttribute('aria-hidden', 'false');
        tooltipEl.classList.add('kpi-tooltip--visible');
        position(target || el);
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
            if (activeEl && (entry.target === activeEl || activeEl.contains(entry.target))) {
                if (!isTruncated(activeEl)) {
                    hide();
                } else {
                    const { target } = getTargetAndText(activeEl);
                    position(target || activeEl);
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
        const elements = root.querySelectorAll ? root.querySelectorAll('[data-truncate-tooltip], .kpi-card') : document.querySelectorAll('[data-truncate-tooltip], .kpi-card');
        elements.forEach(bindElement);
    }

    scan();

    // Event delegation on document to guarantee zero missed hover events
    document.addEventListener('mouseover', (e) => {
        const target = e.target.closest('[data-truncate-tooltip], .kpi-card');
        if (target && !target.dataset.tooltipBound) {
            bindElement(target);
            show(target);
        }
    }, { passive: true });

    document.addEventListener('focusin', (e) => {
        const target = e.target.closest('[data-truncate-tooltip], .kpi-card');
        if (target && !target.dataset.tooltipBound) {
            bindElement(target);
            show(target);
        }
    }, { passive: true });

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

    // Global event listeners for viewport adjustments (zooming, window resizing, scrolling)
    window.addEventListener('resize', () => {
        if (activeEl && tooltipEl && tooltipEl.classList.contains('kpi-tooltip--visible')) {
            if (!isTruncated(activeEl)) {
                hide();
            } else {
                const { target } = getTargetAndText(activeEl);
                position(target || activeEl);
            }
        }
    }, { passive: true });

    window.addEventListener('scroll', () => {
        if (activeEl && tooltipEl && tooltipEl.classList.contains('kpi-tooltip--visible')) {
            const { target } = getTargetAndText(activeEl);
            position(target || activeEl);
        }
    }, { passive: true });
}
