<template>
    <form @submit.prevent="submit" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input v-model.trim="form.email" type="email" class="mt-1 w-full rounded border p-2" required/>
            <p class="text-red-600 text-sm" v-if="errors.email">{{ errors.email[0] }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium">Actor Description</label>
            <textarea
                v-model.trim="form.description"
                rows="5"
                class="mt-1 w-full rounded border p-2"
                required
                placeholder="Example: My name is Keanu Reeves. I live at 101 Hollywood Road, Los Angeles.
I am a male actor, 186 cm tall, weight 82 kg, age 59."
            ></textarea>
            <p class="mt-2 text-xs text-gray-600">
                Please enter your first name and last name, and also provide your address.
            </p>

            <p class="text-red-600 text-sm" v-if="errors.description">{{ errors.description[0] }}</p>
            <p class="text-red-600 text-sm" v-if="serverError">{{ serverError }}</p>
        </div>

        <button :disabled="loading"
                class="rounded bg-indigo-600 px-4 py-2 font-semibold text-white disabled:opacity-60">
            <span v-if="!loading">Submit</span>
            <span v-else>Submitting…</span>
        </button>
    </form>
</template>

<script setup lang="ts">
import {reactive, ref} from 'vue'

type Errors = Record<string, string[]>

const form = reactive<{ email: string; description: string }>({email: '', description: ''})
const errors = reactive<Errors>({})
const loading = ref(false)
const serverError = ref('')

function csrf(): string {
    const el = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')
    return el?.content ?? ''
}

async function submit() {
    loading.value = true
    serverError.value = ''
    Object.keys(errors).forEach(k => delete (errors as any)[k])

    try {
        const res = await fetch('/submit', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(form),
            credentials: 'same-origin',
        })

        if (res.status === 422) {
            const data = await res.json()
            Object.assign(errors, data.errors || {})
            return
        }

        if (!res.ok) {
            serverError.value = 'Something went wrong. Please try again.'
            return
        }

        window.location.href = '/submissions'
    } catch {
        serverError.value = 'Network error. Please retry.'
    } finally {
        loading.value = false
    }
}
</script>
