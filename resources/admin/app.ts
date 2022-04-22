import 'clockwork-browser/metrics'
import 'clockwork-browser/toolbar'

import 'virtual:windi.css'
import './app.css'

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'

import Route from './plugins/route'
import Translations from './plugins/translations'
import DateFns from './plugins/date-fns'
import HeroIcons from './plugins/hero-icons'
import '@quasar/extras/material-icons-round/material-icons-round.css'

// Import Quasar css
import './material-icons/material-icons.css'
import 'quasar/dist/quasar.css'

import { Quasar, Notify, Dialog } from 'quasar'
import VueUploadComponent from 'vue-upload-component'
import { UploadMedia, UpdateMedia } from '@s1modev/media-upload'
import axios from 'axios'
import VueAxios from 'vue-axios'

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.globEager(`./pages/**/*`)

    return pages[`./pages/${name}.vue`].default
  },
  setup({ el, app, props, plugin }) {
    createApp({ render: () => h(app, props) })
      .use(plugin)
      .use(Route)
      .use(Translations)
      .use(DateFns)
      .use(VueAxios, axios)
      .use(Quasar, {
        plugins: {
          Notify,
          Dialog,
        },
      })
      .use(HeroIcons)
      .component('InertiaLink', Link)
      .component('upload-media', UploadMedia)
      .component('update-media', UpdateMedia)
      .component('file-upload', VueUploadComponent)
      .mount(el)
  },
})

InertiaProgress.init({ color: '#4B5563' })
