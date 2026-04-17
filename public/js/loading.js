/**
 * VOLTRONIX LOADING & TRANSITION SYSTEM
 * Provides smooth loading and navigation experience
 */

class VoltronixLoader {
    constructor() {
        this.preloader = null;
        this.progressBar = null;
        this.progressBarContainer = null;
        this.pageTransition = null;
        this.contentWrapper = null;
        this.isLoading = false;
        this.navigationInProgress = false;
        this.pageReadyTimeout = null;
        this.windowLoadTimeout = null;
        this.preloaderRemovalTimeout = null;
        this.progressBarInterval = null;
        this.progressBarResetTimeout = null;
        this.popStateTimeout = null;
        this.transitionTimeout = null;
        this.navigationTimeout = null;

        this.init();
    }

    /**
     * Initialize the loading system
     */
    init() {
        this.createElements();
        this.bindEvents();
        this.handleInitialLoad();
    }

    /**
     * Create loading elements if they don't exist
     */
    createElements() {
        // Create preloader if it doesn't exist
        if (!document.querySelector('.preloader')) {
            this.createPreloader();
        }

        // Create progress bar if it doesn't exist
        if (!document.querySelector('.progress-bar-container')) {
            this.createProgressBar();
        }

        // Create page transition overlay if it doesn't exist
        if (!document.querySelector('.page-transition')) {
            this.createPageTransition();
        }

        // Get references to elements
        this.preloader = document.querySelector('.preloader');
        this.progressBar = document.querySelector('.progress-bar');
        this.progressBarContainer = document.querySelector('.progress-bar-container');
        this.pageTransition = document.querySelector('.page-transition');
        this.contentWrapper = document.querySelector('.content-wrapper') || document.body;
    }

    /**
     * Create preloader element
     */
    createPreloader() {
        const preloader = document.createElement('div');
        preloader.className = 'preloader';
        preloader.innerHTML = `
            <div class="preloader-content">
                <div class="preloader-logo">
                    <img src="/images/logo nt.png" alt="Voltronix" onerror="this.style.display='none'">
                </div>
                <div class="preloader-text">Voltronix</div>
                <div class="preloader-spinner">
                    <div class="spinner-ring"></div>
                </div>
            </div>
        `;
        document.body.appendChild(preloader);
    }

    /**
     * Create progress bar element
     */
    createProgressBar() {
        const progressContainer = document.createElement('div');
        progressContainer.className = 'progress-bar-container';
        progressContainer.innerHTML = '<div class="progress-bar"></div>';
        document.body.appendChild(progressContainer);
    }

    /**
     * Create page transition overlay
     */
    createPageTransition() {
        const transition = document.createElement('div');
        transition.className = 'page-transition';
        transition.innerHTML = `
            <div class="page-transition-content">
                <div class="page-transition-spinner"></div>
                <div class="page-transition-text">Loading...</div>
            </div>
        `;
        document.body.appendChild(transition);
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Handle page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.handlePageReady());
        } else {
            this.handlePageReady();
        }

        // Handle window load (all resources loaded)
        window.addEventListener('load', () => this.handleWindowLoad());

        // Handle bfcache restore
        window.addEventListener('pageshow', (event) => this.handlePageShow(event));

        // Handle navigation clicks
        this.bindNavigationEvents();

        // Handle browser back/forward
        window.addEventListener('popstate', () => this.handlePopState());

        // Handle form submissions
        this.bindFormEvents();
    }

    /**
     * Handle initial page load
     */
    handleInitialLoad() {
        // Show preloader immediately
        if (this.preloader) {
            this.preloader.style.display = 'flex';
            document.body.classList.add('no-scroll');
        }

        // Set minimum loading time to avoid flash
        this.minLoadingTime = Date.now() + 800; // 800ms minimum
    }

    /**
     * Handle when DOM is ready
     */
    handlePageReady() {
        // Wait for minimum loading time
        const remainingTime = this.minLoadingTime - Date.now();
        const delay = Math.max(0, remainingTime);

        this.clearTimeoutRef('pageReadyTimeout');
        this.pageReadyTimeout = setTimeout(() => {
            this.hidePreloader();
            this.showContent();
        }, delay);
    }

    /**
     * Handle when all resources are loaded
     */
    handleWindowLoad() {
        // Ensure preloader is hidden
        this.clearTimeoutRef('windowLoadTimeout');
        this.windowLoadTimeout = setTimeout(() => {
            this.hidePreloader();
            this.showContent();
        }, 100);
    }

    /**
     * Handle bfcache restore and ensure any loader state is cleared immediately
     */
    handlePageShow(event) {
        if (!event.persisted) {
            return;
        }

        this.forceResetLoaderState();
    }

    /**
     * Hide preloader with animation
     */
    hidePreloader() {
        if (this.preloader && !this.preloader.classList.contains('fade-out')) {
            this.preloader.classList.add('fade-out');
            document.body.classList.remove('no-scroll');

            // Remove preloader from DOM after animation
            this.clearTimeoutRef('preloaderRemovalTimeout');
            this.preloaderRemovalTimeout = setTimeout(() => {
                if (this.preloader && this.preloader.parentNode) {
                    this.preloader.remove();
                    this.preloader = null;
                }
            }, 500);
        }
    }

    /**
     * Show main content with animation
     */
    showContent() {
        if (this.contentWrapper) {
            this.contentWrapper.classList.add('loaded');
        }

        // Animate elements that are marked for animation
        this.animateElements();
    }

    /**
     * Animate elements on page load
     */
    animateElements() {
        const animatedElements = document.querySelectorAll('[data-animate]');

        animatedElements.forEach((element, index) => {
            setTimeout(() => {
                const animationType = element.dataset.animate || 'fade-in';
                element.classList.add(animationType);
            }, index * 100); // Stagger animations
        });
    }

    /**
     * Bind navigation events
     */
    bindNavigationEvents() {
        // Handle all internal links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');

            if (link && this.shouldInterceptNavigation(link)) {
                e.preventDefault();
                this.navigateWithTransition(link.href);
            }
        });
    }

    /**
     * Check if navigation should be intercepted
     */
    shouldInterceptNavigation(link) {
        // Don't intercept if:
        // - External link
        // - Has target="_blank"
        // - Has download attribute
        // - Is a hash link
        // - Navigation already in progress

        if (this.navigationInProgress) return false;
        if (link.target === '_blank') return false;
        if (link.hasAttribute('download')) return false;
        if (link.href.includes('#') && link.href.split('#')[0] === window.location.href.split('#')[0]) return false;
        if (link.href.startsWith('mailto:') || link.href.startsWith('tel:')) return false;

        // Check if it's an internal link
        try {
            const linkUrl = new URL(link.href);
            const currentUrl = new URL(window.location.href);
            return linkUrl.origin === currentUrl.origin;
        } catch {
            return false;
        }
    }

    /**
     * Navigate with smooth transition
     */
    navigateWithTransition(url) {
        if (this.navigationInProgress) return;

        this.navigationInProgress = true;

        // Show progress bar
        this.showProgressBar();

        // Show page transition overlay (not as a separate page)
        this.clearTimeoutRef('transitionTimeout');
        this.transitionTimeout = setTimeout(() => {
            this.showPageTransition();
        }, 150);

        // Navigate to new page without adding loading screen to history
        this.clearTimeoutRef('navigationTimeout');
        this.navigationTimeout = setTimeout(() => {
            // Use location.replace to avoid history entry for transition
            window.location.href = url;
        }, 300);
    }

    /**
     * Show progress bar
     */
    showProgressBar() {
        if (this.progressBarContainer) {
            this.progressBarContainer.classList.add('active');

            // Animate progress
            let progress = 0;
            this.clearIntervalRef('progressBarInterval');
            this.progressBarInterval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;

                if (this.progressBar) {
                    this.progressBar.style.width = progress + '%';
                }

                if (progress >= 90) {
                    this.clearIntervalRef('progressBarInterval');
                }
            }, 100);
        }
    }

    /**
     * Complete progress bar
     */
    completeProgressBar() {
        if (this.progressBar) {
            this.clearIntervalRef('progressBarInterval');
            this.progressBar.style.width = '100%';

            this.clearTimeoutRef('progressBarResetTimeout');
            this.progressBarResetTimeout = setTimeout(() => {
                if (this.progressBarContainer) {
                    this.progressBarContainer.classList.remove('active');
                    this.progressBar.style.width = '0%';
                }
            }, 200);
        }
    }

    /**
     * Show page transition overlay
     */
    showPageTransition() {
        if (this.pageTransition) {
            this.pageTransition.classList.add('active');
            document.body.classList.add('no-scroll');
        }
    }

    /**
     * Hide page transition overlay
     */
    hidePageTransition() {
        if (this.pageTransition) {
            this.pageTransition.classList.remove('active');
            document.body.classList.remove('no-scroll');
        }
    }

    /**
     * Handle browser back/forward navigation
     */
    handlePopState() {
        // Show brief loading indicator for back/forward navigation
        this.showProgressBar();

        this.clearTimeoutRef('popStateTimeout');
        this.popStateTimeout = setTimeout(() => {
            this.completeProgressBar();
        }, 200);
    }

    /**
     * Force clear any active loader UI immediately.
     * This is used for bfcache restores where normal load events do not replay.
     */
    forceResetLoaderState() {
        this.clearPendingTimers();
        this.navigationInProgress = false;

        document.body.classList.remove('no-scroll');

        if (this.preloader) {
            this.preloader.classList.remove('fade-out');
            this.preloader.style.display = 'none';

            if (this.preloader.parentNode) {
                this.preloader.remove();
            }

            this.preloader = null;
        }

        if (this.pageTransition) {
            this.pageTransition.classList.remove('active');
        }

        if (this.progressBarContainer) {
            this.progressBarContainer.classList.remove('active');
        }

        if (this.progressBar) {
            this.progressBar.style.width = '0%';
        }

        this.showContent();
    }

    clearPendingTimers() {
        this.clearTimeoutRef('pageReadyTimeout');
        this.clearTimeoutRef('windowLoadTimeout');
        this.clearTimeoutRef('preloaderRemovalTimeout');
        this.clearTimeoutRef('progressBarResetTimeout');
        this.clearTimeoutRef('popStateTimeout');
        this.clearTimeoutRef('transitionTimeout');
        this.clearTimeoutRef('navigationTimeout');
        this.clearIntervalRef('progressBarInterval');
    }

    clearTimeoutRef(property) {
        if (this[property]) {
            clearTimeout(this[property]);
            this[property] = null;
        }
    }

    clearIntervalRef(property) {
        if (this[property]) {
            clearInterval(this[property]);
            this[property] = null;
        }
    }

    /**
     * Bind form submission events
     */
    bindFormEvents() {
        document.addEventListener('submit', (e) => {
            const form = e.target;

            // Don't show loading for AJAX forms
            if (form.classList.contains('ajax-form')) return;

            // Show loading state for form submission
            this.showFormLoading(form);
        });
    }

    /**
     * Show loading state for form
     */
    showFormLoading(form) {
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

        if (submitButton) {
            submitButton.classList.add('btn-loading');
            submitButton.disabled = true;

            // Store original text
            const originalText = submitButton.textContent || submitButton.value;
            submitButton.dataset.originalText = originalText;

            // Update button text
            if (submitButton.tagName === 'BUTTON') {
                submitButton.innerHTML = '<span class="btn-text">' + originalText + '</span>';
            }
        }
    }

    /**
     * Hide loading state for form
     */
    hideFormLoading(form) {
        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

        if (submitButton) {
            submitButton.classList.remove('btn-loading');
            submitButton.disabled = false;

            // Restore original text
            const originalText = submitButton.dataset.originalText;
            if (originalText) {
                if (submitButton.tagName === 'BUTTON') {
                    submitButton.textContent = originalText;
                } else {
                    submitButton.value = originalText;
                }
            }
        }
    }

    /**
     * Show loading state for specific element
     */
    showElementLoading(element, text = 'Loading...') {
        element.classList.add('btn-loading');
        element.disabled = true;

        if (element.tagName === 'BUTTON') {
            element.dataset.originalText = element.textContent;
            element.innerHTML = '<span class="btn-text">' + element.textContent + '</span>';
        }
    }

    /**
     * Hide loading state for specific element
     */
    hideElementLoading(element) {
        element.classList.remove('btn-loading');
        element.disabled = false;

        if (element.dataset.originalText) {
            element.textContent = element.dataset.originalText;
            delete element.dataset.originalText;
        }
    }

    /**
     * Create skeleton loading placeholder
     */
    createSkeleton(container, type = 'card') {
        const skeleton = document.createElement('div');
        skeleton.className = 'skeleton';

        switch (type) {
            case 'text':
                skeleton.className += ' skeleton-text';
                break;
            case 'card':
                skeleton.className += ' skeleton-card';
                break;
            case 'avatar':
                skeleton.className += ' skeleton-avatar';
                break;
        }

        container.appendChild(skeleton);
        return skeleton;
    }

    /**
     * Remove all skeletons from container
     */
    removeSkeletons(container) {
        const skeletons = container.querySelectorAll('.skeleton');
        skeletons.forEach(skeleton => skeleton.remove());
    }

    /**
     * Utility method to show loading overlay
     */
    showLoadingOverlay(message = 'Loading...') {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-overlay-content">
                <div class="loading-spinner"></div>
                <div class="loading-message">${message}</div>
            </div>
        `;

        document.body.appendChild(overlay);
        return overlay;
    }

    /**
     * Hide loading overlay
     */
    hideLoadingOverlay(overlay) {
        if (overlay && overlay.parentNode) {
            overlay.remove();
        }
    }
}

// Initialize the loading system when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.voltronixLoader = new VoltronixLoader();
    });
} else {
    window.voltronixLoader = new VoltronixLoader();
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = VoltronixLoader;
}
