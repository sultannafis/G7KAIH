<div id="global-page-loader" style="position: fixed; inset: 0; z-index: 999999; background: linear-gradient(135deg, #f0f9ff 0%, #bae6fd 100%); display: flex; align-items: center; justify-content: center; transition: opacity 0.6s ease, visibility 0.6s ease;">
    <div style="position: relative; width: 140px; height: 140px; display: flex; align-items: center; justify-content: center;">
        <style>
            @keyframes spin-loader { 100% { transform: rotate(360deg); } }
            @keyframes pulse-loader { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(0.95); opacity: 0.8; } }
        </style>
        
        <!-- Spinning Rings around the logo -->
        <div style="position: absolute; inset: 0; border: 4px solid rgba(14, 165, 233, 0.15); border-radius: 50%;"></div>
        <div style="position: absolute; inset: 0; border: 4px solid #0EA5E9; border-top-color: transparent; border-radius: 50%; animation: spin-loader 1s linear infinite;"></div>
        
        <!-- Encased Logo Image -->
        <img src="{{ asset('images/G7KAIH-Blue.png') }}" alt="G7KAIH Loading..." style="width: 110px; height: auto; animation: pulse-loader 2s ease-in-out infinite;">
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loader = document.getElementById('global-page-loader');
        if (loader) {
            // Hide the loader once the page is fully loaded
            window.addEventListener('load', function() {
                setTimeout(function() {
                    loader.style.opacity = '0';
                    setTimeout(function() {
                        loader.style.display = 'none';
                    }, 600); // Wait for opacity transition to finish
                }, 300); // Slight delay for aesthetics
            });

            // Show loader immediately when navigating away (clicking links)
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && link.href && !link.href.startsWith('javascript:')) {
                    // Check if it's an internal link, not opening in new tab, not an anchor link, and not a download
                    const isInternal = link.hostname === window.location.hostname;
                    const isNotAnchor = !link.href.includes('#') || link.pathname !== window.location.pathname;
                    const isNotNewTab = link.target !== '_blank';
                    const isNotDownload = !link.hasAttribute('download');
                    
                    if (isInternal && isNotAnchor && isNotNewTab && isNotDownload) {
                        loader.style.display = 'flex';
                        // Use a short timeout to ensure display: flex applies before changing opacity
                        setTimeout(() => {
                            loader.style.opacity = '1';
                        }, 10);
                    }
                }
            });

            // Show loader immediately on form submissions
            document.addEventListener('submit', function(e) {
                const form = e.target;
                if (!form.hasAttribute('target') || form.target !== '_blank') {
                    loader.style.display = 'flex';
                    setTimeout(() => {
                        loader.style.opacity = '1';
                    }, 10);
                }
            });
        }
    });

    // Fallback for beforeunload just in case for other navigations
    window.addEventListener('pagehide', function() {
        const loader = document.getElementById('global-page-loader');
        if (loader) {
            loader.style.display = 'flex';
            loader.style.opacity = '1';
        }
    });
</script>
