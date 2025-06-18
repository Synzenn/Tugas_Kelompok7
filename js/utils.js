// js/utils.js

// Definisi global Utils yang akan diakses oleh semua script
const Utils = {
    storage: {
        set: function(key, value) {
            try {
                localStorage.setItem(key, JSON.stringify(value));
                return true;
            } catch (e) {
                console.error('Utils.storage.set: Error saving to localStorage:', e);
                return false;
            }
        },
        get: function(key, defaultValue = null) {
            try {
                const item = localStorage.getItem(key);
                return item ? JSON.parse(item) : defaultValue;
            } catch (e) {
                console.error('Utils.storage.get: Error reading from localStorage:', e);
                return defaultValue;
            }
        },
        remove: function(key) {
            try {
                localStorage.removeItem(key);
                return true;
            } catch (e) {
                console.error('Utils.storage.remove: Error removing from localStorage:', e);
                return false;
            }
        }
    },
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    validateEmail: function(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
};

// Expose Utils globally
window.Utils = Utils;
console.log("js/utils.js: Utils object loaded and exposed globally."); // Debugging