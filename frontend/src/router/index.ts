import { createRouter, createWebHistory } from 'vue-router'
import ListPokemonView from '../views/ListPokemonView.vue'
import DetailPokemonView from '../views/DetailPokemonView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: ListPokemonView,
    },
    {
      path: '/detail/:id',
      name: 'ListPokemonView',
      component: DetailPokemonView,
      props: true,
    },
    // {
    //   path: '/about',
    //   name: 'about',
    //   // route level code-splitting
    //   // this generates a separate chunk (About.[hash].js) for this route
    //   // which is lazy-loaded when the route is visited.
    //   component: () => import('../views/AboutView.vue'),
    // },
  ],
})

export default router
