import { createRouter, createWebHistory }  from "vue-router";

import invoiceIndex from '../components/Invoices/index.vue';

import Notfound from "../components/Notfound.vue";

import createInvoice from "../components/Invoices/new.vue";

import showInvoice from "../components/Invoices/show.vue";

import editInvoice from "../components/Invoices/edit.vue";

const routes = [
    {
        path:'/',
        component:invoiceIndex
    },
    {
        path:'/create/invoice',
        component:createInvoice
    },
    {
        path:'/invoice/show/:id',
        component:showInvoice,
        props:true
    },
    {
        path:'/invoice/edit/:id',
        component:editInvoice,
        props:true
    },
    {
        path:'/:pathMatch(.*)*',
        component:Notfound
    }
]
const router = createRouter({
    history:createWebHistory(),
    routes
})

export default router 