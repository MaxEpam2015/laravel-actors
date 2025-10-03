import '../css/app.css'

import { createApp } from 'vue'
import CreateActorForm from './components/CreateActorForm.vue'

const el = document.querySelector('#actor-form-app')
if (el) {
    createApp(CreateActorForm).mount(el)
}
