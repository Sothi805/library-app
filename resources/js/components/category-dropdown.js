document.addEventListener('alpine:init', () => {
    window.Alpine.data('categoryDropdown', () => ({
    categories: [],
    filtered: [],
    search: '',
    selected: null,
    open: false,
    loading: true,
    activeIndex: -1,

    async init() {
        await this.fetchCategories();
        this.loading = false;

        // React instantly on typing
        this.$watch('search', () => this.onType());
    },

    async fetchCategories() {
        try {
            const res = await fetch('/book-categories', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
            });
            const data = await res.json();
            this.categories = data.categories.map(c => ({
                ...c,
                editing: false,
                tempName: c.name,
            })) || [];
            this.filtered = [...this.categories];
        } catch (err) {
            console.error('Failed to fetch categories:', err);
            this.categories = [];
            this.filtered = [];
        } finally {
            this.loading = false;
        }
    },

    onType() {
        this.filterCategories();
        this.open = true;
    },

    toggleDropdown() {
        this.open = !this.open;
        if (this.open) this.filterCategories();
    },

    closeDropdown() {
        setTimeout(() => (this.open = false), 80);
    },

    filterCategories() {
        const s = this.search.trim().toLowerCase();
        this.filtered = s
            ? this.categories.filter(c => c.name.toLowerCase().includes(s))
            : [...this.categories];
    },

    // Check if search matches any existing category exactly
    get exactMatch() {
        const s = this.search.trim().toLowerCase();
        return this.categories.some(c => c.name.toLowerCase() === s);
    },

    // Check if we should show "Add new" option
    get shouldShowAddNew() {
        return this.search.trim() !== '' && !this.exactMatch;
    },

    selectCategory(category) {
        this.selected = category;
        this.search = category.name;
        this.open = false;
    },

    async addCategory() {
        const name = this.search.trim();
        if (!name) return;
        this.loading = true;

        try {
            const res = await fetch('/book-categories', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                credentials: 'same-origin',
                body: JSON.stringify({ name }),
            });
            const data = await res.json();

            if (res.ok && data.success) {
                const newCategory = { ...data.category, editing: false, tempName: data.category.name };
                this.categories.push(newCategory);
                this.filtered = [...this.categories];
                this.selected = newCategory;
                this.search = newCategory.name;
                this.open = false;
            } else {
                alert('Failed to add category');
            }
        } catch (error) {
            console.error('Error adding category:', error);
        } finally {
            this.loading = false;
        }
    },

    async updateCategory(category, newName) {
        if (!newName.trim() || newName === category.name) {
            category.editing = false;
            return;
        }
        this.loading = true;

        try {
            const res = await fetch(`/book-categories/${category.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                credentials: 'same-origin',
                body: JSON.stringify({ name: newName }),
            });
            const data = await res.json();
            if (data.success) {
                category.name = newName;
                category.editing = false;
                this.filterCategories();
            } else {
                alert('Failed to update category');
            }
        } catch (err) {
            console.error('Error updating category:', err);
        } finally {
            this.loading = false;
        }
    },

    async deleteCategory(category) {
        if (!confirm(`Delete "${category.name}"?`)) return;
        this.loading = true;

        try {
            const res = await fetch(`/book-categories/${category.id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                credentials: 'same-origin',
            });
            const data = await res.json();
            if (data.success) {
                this.categories = this.categories.filter(c => c.id !== category.id);
                this.filterCategories();
            } else {
                alert('Failed to delete category');
            }
        } catch (err) {
            console.error('Error deleting category:', err);
        } finally {
            this.loading = false;
        }
    },
}));
});
