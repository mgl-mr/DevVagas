import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/perfil',
    name: 'Perfil',
    component: () => import(/* webpackChunkName: "perfil" */ '../views/Perfil.vue')
  },
  {
    path: '/curriculo',
    name: 'Curriculo',
    component: () => import(/* webpackChunkName: "perfil" */ '../views/Curriculo.vue')
  },
  {
    path: '/vagas',
    name: 'Vagas',
    component: () => import(/* webpackChunkName: "perfil" */ '../views/Vagas.vue')
  },
  {
    path: '/envios',
    name: 'Meus envios',
    component: () => import(/* webpackChunkName: "perfil" */ '../views/Envios.vue')
  },
  {
    path: '/recebidos',
    name: 'Recebidos',
    component: () => import(/* webpackChunkName: "perfil" */ '../views/Recebidos.vue')
  }

]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
