class Loading {
    static show() {
        document.body.className +=' loading';
    }

    static hide() {
        document.body.className = document.body.className.replace('loading','');
    }
}

export default Loading;