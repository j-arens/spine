const searchModal = (() => {

    let modal,
        toggles;

    const toggleModal = (e) => {
        e.preventDefault();
        modal.classList.toggle('search-modal--is-hidden');
    }

    const bindEvents = () => {
        try {
            toggles.forEach(toggle => toggle.addEventListener('click', toggleModal));
        } catch(e) {
            console.error('SEARCH_MODAL: Unable to bind events!', e);
        }
    }

    const cacheDom = () => {
        modal = document.getElementById('js-search-modal');
        toggles = Array.from(document.querySelectorAll('.js-search-toggle'));
    }

    const main = () => {
        cacheDom();
        bindEvents();
    }

    const domReady = (...fnx) => {
        if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
            fnx.forEach(fn => fn());
        } else {
            document.addEventListener('DOMContentLoaded', () => fnx.forEach(fn => fn()));
        }
    }

    return {
        init() {
            domReady(main);
        }
    }

})();

export default searchModal;