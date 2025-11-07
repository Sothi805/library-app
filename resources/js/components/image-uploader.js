import Alpine from 'alpinejs';

Alpine.data('imageUploader', () => ({
    preview: null,
    dragging: false,

    handleDrop(e) {
        this.dragging = false;
        const file = e.dataTransfer.files[0];
        this.previewFile(file);
        document.querySelector('#cover').files = e.dataTransfer.files;
    },

    previewImage(e) {
        const file = e.target.files[0];
        this.previewFile(file);
    },

    previewFile(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => (this.preview = e.target.result);
            reader.readAsDataURL(file);
        } else {
            alert('Please upload a valid image file (JPG, PNG, WEBP).');
        }
    },
}));

