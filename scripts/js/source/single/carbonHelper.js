const carbonHelper = ((templates) => {

    let templateSelector,
        editorContainer;

    const flattenArr = (arr) => {
        const flattened = [];

        for(let i = 0; i < arr.length; i++) {
            flattened.push(...arr[i]);
        }

        return flattened;
    }

    const toggleEditor = (template, editor) => {
        if (flattenArr(templates).includes(template) && !editor.hidden) {
            editor.hide();
            editorContainer.classList.add('carbon-hide-editor');
        } else if (editor.hidden) {
            editorContainer.classList.remove('carbon-hide-editor');
            editor.show();
        }
    }

    const handleChange = (e) => {
        if (!e.target.value || !editorContainer) return;

        const template = e.target.value;
        const mceEditor = window.tinymce.get(window.wpActiveEditor);

        if (mceEditor) {
            toggleEditor(template, mceEditor);
        } else {
            setTimeout(() => handleChange(e), 25);
        }
    }

    const bindEvents = () => {
        try {
            templateSelector.addEventListener('change', handleChange);
        } catch(e) {
            console.error('CARBON_HELPERS: Unable to bind events!', e);
        }
    }

    const cacheDom = () => {
        templateSelector = document.getElementById('page_template');
        editorContainer = document.getElementById('postdivrich');
    }

    const main = () => {
        cacheDom();
        bindEvents();
    }
    
    const docReady = (...fnx) => {
        if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
            fnx.forEach(fn => fn());
        } else {
            document.addEventListener('DOMContentLoaded', () => fnx.forEach(fn => fn()));
        }
    }

    return {
        init() {
            docReady(main);
        }
    }

})(window.carbonTemplates);

carbonHelper.init();