import './bootstrap';
import Alpine from 'alpinejs';
import { v4 as uuidv4 } from 'uuid';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('sidebarData', () => ({
        menu: [
            {
                id: uuidv4(),
                faIcon: 'fa-solid fa-users',
                title: 'Users',
                links: [
                    {title: 'list', url: '/users'},
                    {title: 'create', url: '/users/create'},
                ],
            },
            {
                id: uuidv4(),
                faIcon: 'fa-solid fa-tag',
                title: 'Tags',
                links: [
                    {title: 'list', url: '/tags'},
                    {title: 'create', url: '/tags/create'},
                ],
            },
            {
                id: uuidv4(),
                faIcon: 'fa-solid fa-bars-staggered',
                title: 'Categories',
                links: [
                    {title: 'list', url: '/categories'},
                    {title: 'create', url: '/categories/create'},
                ],
            },
        ],
    }))
})

Alpine.start();
