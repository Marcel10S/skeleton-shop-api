/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

const categoryTree = document.querySelector('[data-category-tree]');

if (categoryTree) {
    const getChildren = (categoryId) => Array.from(
        categoryTree.querySelectorAll(`[data-parent-id="${categoryId}"]`),
    );

    const setDescendantsHidden = (categoryId, hidden) => {
        getChildren(categoryId).forEach((row) => {
            row.hidden = hidden;
            setDescendantsHidden(row.dataset.categoryId, hidden);
        });
    };

    const revealExpandedDescendants = (categoryId) => {
        getChildren(categoryId).forEach((row) => {
            row.hidden = false;

            const toggle = row.querySelector('[data-category-toggle]');
            if (toggle && toggle.getAttribute('aria-expanded') === 'true') {
                revealExpandedDescendants(row.dataset.categoryId);
            } else {
                setDescendantsHidden(row.dataset.categoryId, true);
            }
        });
    };

    categoryTree.addEventListener('click', (event) => {
        const button = event.target.closest('[data-category-toggle]');

        if (!button) {
            return;
        }

        const expanded = button.getAttribute('aria-expanded') === 'true';
        const categoryId = button.dataset.categoryToggle;

        button.setAttribute('aria-expanded', String(!expanded));
        button.setAttribute('aria-label', expanded ? 'Expand category' : 'Collapse category');

        if (expanded) {
            setDescendantsHidden(categoryId, true);
        } else {
            revealExpandedDescendants(categoryId);
        }
    });
}

const orderForm = document.querySelector('[data-order-form]');

if (orderForm) {
    const items = orderForm.querySelector('[data-order-items]');

    orderForm.addEventListener('click', (event) => {
        if (event.target.closest('[data-order-item-add]')) {
            const index = Number(items.dataset.index);
            const item = items.dataset.prototype.replace(/__name__/g, index);

            items.insertAdjacentHTML('beforeend', item);
            items.dataset.index = String(index + 1);
        }

        const removeButton = event.target.closest('[data-order-item-remove]');
        if (removeButton) {
            removeButton.closest('[data-order-item]').remove();
        }
    });
}
