/**
 * Admin.js - Point d'entrée admin optimisé
 * 
 * Migration progressive vers une architecture moderne
 * - Suppression de jQuery global
 * - Modules ES6+ avec lazy loading
 * - Architecture component-based
 * 
 * @author Prudence Dieudonné ASSOGBA
 * @version 2.0 - 2025
 */

// Import des styles
import './styles/admin.scss';

// Import Bootstrap
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;


// Import conditionnel des composants (lazy loading)
const loadComponents = async () => {
    try {
        // Chargement différé des composants lourds
        const [
            { default: MediaPicker },
            { default: CustomEditor },
            { default: MediaSelector }
        ] = await Promise.all([
            import('./js/components/media-picker.js'),
            import('./js/components/custom-editor.js'),
            import('./js/components/media-selector.js')
        ]);
        
        // Initialisation conditionnelle des composants
        initializeComponents();
        
    } catch (error) {
        console.warn('Erreur lors du chargement des composants admin:', error);
        // Continuer sans les composants
    }
};

/**
 * Initialise les composants détectés sur la page
 */
function initializeComponents() {
    // Initialisation différée des éditeurs personnalisés
    initializeCustomEditors();
    
    // Autres initialisations...
}

/**
 * Initialise les éditeurs personnalisés avec une approche moderne
 */
function initializeCustomEditors() {
    const textareas = document.querySelectorAll('textarea.custom-editor');
    
    textareas.forEach(textarea => {
        const enableEditor = textarea.dataset.enableEditor === 'true' || 
                           textarea.dataset.enableEditor === true;
        
        if (enableEditor) {
            const options = {
                height: parseInt(textarea.dataset.editorHeight) || 300,
                enableMedia: textarea.dataset.enableMedia === 'true',
                enableImageResize: textarea.dataset.enableImageResize === 'true',
                enableDragDrop: textarea.dataset.enableDragDrop === 'true',
                maxImageWidth: parseInt(textarea.dataset.maxImageWidth) || 800,
                minImageWidth: parseInt(textarea.dataset.minImageWidth) || 50,
                enableAutoSave: true,
                autoSaveInterval: 30000,
                placeholder: textarea.placeholder || getTranslation('editor.placeholder'),
                toolbar: [
                    'bold', 'italic', 'underline', '|',
                    'h1', 'h2', 'h3', '|',
                    'link', 'unlink', '|',
                    'image', 'media', '|',
                    'unorderedList', 'orderedList', '|',
                    'blockquote', 'code', '|',
                    'fullscreen', 'source', 'wordcount'
                ]
            };
            
            // Import dynamique de CustomEditor si disponible
            import('./js/components/custom-editor.js')
                .then(({ default: CustomEditor }) => {
                    if (typeof CustomEditor !== 'undefined') {
                        const editor = new CustomEditor(textarea, options);
                        textarea.dataset.customEditorInstance = editor;
                    }
                })
                .catch(error => {
                    console.warn('CustomEditor non disponible:', error);
                    // Fallback vers l'ancienne version si elle existe
                    if (typeof $ !== 'undefined' && $.fn.customEditor) {
                        $(textarea).customEditor(options);
                    }
                });
        }
    });
}

// Chargement différé des composants
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadComponents);
} else {
    loadComponents();
}

