<style>
/* Global Custom Select Styles */
.g7-custom-select-wrapper { position: relative; display: inline-block; width: 100%; }
.g7-custom-select-btn {
    width: 100%; border-radius: 0.75rem; border: 1px solid rgba(186,230,253,0.8);
    background: rgba(255,255,255,0.7); color: #0c4a6e;
    padding: 0.625rem 1rem; font-size: 0.875rem; text-align: left;
    display: flex; justify-content: space-between; align-items: center;
    cursor: pointer; transition: all 0.2s ease;
    min-height: 2.75rem;
}
.dark .g7-custom-select-btn { background: rgba(8,47,73,0.5); border-color: rgba(14,165,233,0.3); color: #e0f2fe; }
.g7-custom-select-btn:focus, .g7-custom-select-btn.focus { outline: none; border-color: #38bdf8; box-shadow: 0 0 0 3px rgba(56,189,248,0.15); }

.g7-custom-select-menu {
    position: absolute; z-index: 999999;
    background: rgba(255,255,255,0.95); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(186,230,253,0.8); border-radius: 0.75rem;
    box-shadow: 0 10px 25px -5px rgba(14,165,233,0.15);
    max-height: 280px; overflow-y: auto; opacity: 0; visibility: hidden;
    transform: translateY(-5px); transition: all 0.2s ease;
}
.dark .g7-custom-select-menu { background: rgba(8,47,73,0.95); border-color: rgba(14,165,233,0.3); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5); }
.g7-custom-select-menu.open { opacity: 1; visibility: visible; transform: translateY(0); }

.g7-custom-select-group { 
    padding: 0.75rem 1rem 0.25rem; font-size: 0.7rem; font-weight: 800; 
    color: #0ea5e9; text-transform: uppercase; letter-spacing: 0.05em; 
}
.g7-custom-select-option {
    padding: 0.5rem 1rem; font-size: 0.875rem; color: #0369a1; cursor: pointer;
    transition: background 0.15s ease; word-break: break-word;
}
.dark .g7-custom-select-option { color: #bae6fd; }
.g7-custom-select-option:hover { background: rgba(224,242,254,0.6); }
.dark .g7-custom-select-option:hover { background: rgba(14,165,233,0.2); }
.g7-custom-select-option.selected { background: #e0f2fe; color: #0284c7; font-weight: 600; }
.dark .g7-custom-select-option.selected { background: rgba(14,165,233,0.3); color: #38bdf8; }
.g7-custom-select-option.disabled { opacity: 0.5; cursor: not-allowed; background: transparent !important; }

/* Scrollbar */
.g7-custom-select-menu::-webkit-scrollbar { width: 6px; }
.g7-custom-select-menu::-webkit-scrollbar-track { background: transparent; }
.g7-custom-select-menu::-webkit-scrollbar-thumb { background: rgba(186,230,253,0.8); border-radius: 10px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    function initCustomSelects() {
        const selects = document.querySelectorAll('select:not(.g7-custom-select-initialized)');
        selects.forEach(select => {
            // Skip multiple selects
            if (select.multiple) return;
            // Skip dynamically generated Alpine selects or specific ignore classes if needed
            if (select.classList.contains('ignore-custom-select')) return;
            
            select.classList.add('g7-custom-select-initialized');
            // Hide native select
            select.style.display = 'none';

            const wrapper = document.createElement('div');
            wrapper.className = 'g7-custom-select-wrapper';
            
            // Transfer width classes
            if (select.className.includes('w-full')) wrapper.classList.add('w-full');
            else if (select.className.includes('w-')) {
                // Try to catch common tailwind widths (e.g. w-48, w-max)
                const match = select.className.match(/\bw-[^\s]+\b/);
                if(match) wrapper.classList.add(match[0]);
            }
            if (select.className.includes('sm:w-auto')) wrapper.classList.add('sm:w-auto');
            
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'g7-custom-select-btn';
            
            // Transfer padding/font classes if they exist to make small selects fit perfectly
            if (select.className.includes('py-1')) {
                btn.style.paddingTop = '0.25rem'; 
                btn.style.paddingBottom = '0.25rem'; 
                btn.style.minHeight = '2rem';
            }
            if (select.className.includes('text-xs')) btn.style.fontSize = '0.75rem';

            const labelSpan = document.createElement('span');
            labelSpan.className = 'truncate';
            
            const iconSvg = document.createElement('div');
            iconSvg.innerHTML = `<svg class="w-4 h-4 text-sky-500 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>`;
            
            btn.appendChild(labelSpan);
            btn.appendChild(iconSvg.firstChild);

            const menu = document.createElement('div');
            menu.className = 'g7-custom-select-menu';

            // Build menu items based on native options
            function buildMenu() {
                menu.innerHTML = '';
                const children = Array.from(select.children);
                children.forEach(child => {
                    if (child.tagName.toLowerCase() === 'optgroup') {
                        const groupLabel = document.createElement('div');
                        groupLabel.className = 'g7-custom-select-group';
                        groupLabel.textContent = '— ' + child.label + ' —';
                        menu.appendChild(groupLabel);
                        
                        Array.from(child.children).forEach(opt => {
                            if (opt.tagName.toLowerCase() === 'option') {
                                createOption(opt);
                            }
                        });
                    } else if (child.tagName.toLowerCase() === 'option') {
                        createOption(child);
                    }
                });
            }

            function createOption(nativeOption) {
                // Don't render completely empty options if they don't have text
                if(!nativeOption.textContent.trim() && !nativeOption.value) return;

                const optDiv = document.createElement('div');
                optDiv.className = 'g7-custom-select-option';
                optDiv.textContent = nativeOption.textContent;
                optDiv.dataset.value = nativeOption.value;
                
                if (nativeOption.disabled) {
                    optDiv.classList.add('disabled');
                } else {
                    optDiv.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (select.value !== nativeOption.value) {
                            select.value = nativeOption.value;
                            // Dispatch change event so Alpine/Livewire/Vanilla JS picks it up
                            select.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                        updateSelected();
                        closeMenu();
                    });
                }
                menu.appendChild(optDiv);
            }

            function updateSelected() {
                const selectedOpt = select.options[select.selectedIndex];
                labelSpan.textContent = selectedOpt ? selectedOpt.textContent : '-- Pilih --';
                
                Array.from(menu.querySelectorAll('.g7-custom-select-option')).forEach(el => {
                    if (el.dataset.value === select.value) {
                        el.classList.add('selected');
                    } else {
                        el.classList.remove('selected');
                    }
                });
            }

            function closeMenu() {
                menu.classList.remove('open');
                btn.querySelector('svg').style.transform = '';
                btn.classList.remove('focus');
            }

            function openMenu() {
                // Close others
                document.querySelectorAll('.g7-custom-select-menu.open').forEach(m => {
                    if(m !== menu) {
                        m.classList.remove('open');
                        const id = m.dataset.btnId;
                        if(id) {
                            const b = document.getElementById(id);
                            if(b) {
                                b.classList.remove('focus');
                                const svg = b.querySelector('svg');
                                if(svg) svg.style.transform = '';
                            }
                        }
                    }
                });

                // Update menu items in case the native select was changed dynamically by Alpine.js
                buildMenu();
                updateSelected();

                menu.classList.add('open');
                btn.querySelector('svg').style.transform = 'rotate(180deg)';
                btn.classList.add('focus');
                
                // Position in document.body
                const rect = btn.getBoundingClientRect();
                const menuHeight = menu.scrollHeight > 280 ? 280 : menu.scrollHeight; // max-height is 280px
                
                menu.style.width = rect.width + 'px';
                menu.style.left = (rect.left + window.scrollX) + 'px';

                // If there isn't enough space below, but there is above, open upwards
                if (rect.bottom + menuHeight > window.innerHeight && rect.top > menuHeight) {
                    menu.style.top = (rect.top + window.scrollY - menuHeight - 4) + 'px';
                } else {
                    menu.style.top = (rect.bottom + window.scrollY + 4) + 'px';
                }
            }

            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (menu.classList.contains('open')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    closeMenu();
                }
            });

            // If select value is changed externally (by Alpine x-model or standard JS)
            select.addEventListener('change', updateSelected);

            buildMenu();
            updateSelected();

            // Link menu to button
            const uniqueId = 'g7-btn-' + Math.random().toString(36).substr(2, 9);
            btn.id = uniqueId;
            menu.dataset.btnId = uniqueId;

            wrapper.appendChild(btn);
            document.body.appendChild(menu); // Append directly to body to escape z-index issues
            select.parentNode.insertBefore(wrapper, select.nextSibling);

            // Update position on scroll if menu is open
            window.addEventListener('scroll', () => {
                if(menu.classList.contains('open')) {
                    const rect = btn.getBoundingClientRect();
                    const menuHeight = menu.scrollHeight > 280 ? 280 : menu.scrollHeight;
                    menu.style.left = (rect.left + window.scrollX) + 'px';
                    if (rect.bottom + menuHeight > window.innerHeight && rect.top > menuHeight) {
                        menu.style.top = (rect.top + window.scrollY - menuHeight - 4) + 'px';
                    } else {
                        menu.style.top = (rect.bottom + window.scrollY + 4) + 'px';
                    }
                }
            }, true); // Use capture phase to catch all scroll events, including inner containers
            
            // Re-position on resize
            window.addEventListener('resize', () => {
                if(menu.classList.contains('open')) {
                    const rect = btn.getBoundingClientRect();
                    menu.style.width = rect.width + 'px';
                    menu.style.left = (rect.left + window.scrollX) + 'px';
                }
            }, { passive: true });
        });
    }

    initCustomSelects();

    // Re-run for dynamically added selects (e.g. Modals, Livewire responses)
    const observer = new MutationObserver((mutations) => {
        let shouldInit = false;
        mutations.forEach(mutation => {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === 1) { 
                        if (node.tagName === 'SELECT' || node.querySelector('select')) {
                            shouldInit = true;
                        }
                    }
                });
            }
        });
        if (shouldInit) {
            setTimeout(initCustomSelects, 10);
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
});
</script>
