<template>
  <article class="rounded-[2.25rem] border-4 border-cyan-200 bg-white p-3 shadow-2xl shadow-slate-950/25 dark:border-cyan-500/30 dark:bg-slate-950 dark:shadow-slate-950/60">
    <div class="overflow-hidden rounded-3xl bg-white dark:bg-slate-900">
      <div class="bg-gradient-to-b from-sky-200 via-sky-100 to-white dark:from-sky-900/40 dark:via-slate-950 dark:to-slate-900">
        <div class="relative w-full pt-[100%]">
          <img
            v-if="tool.gambar"
            :src="tool.gambar"
            :alt="`Gambar ${tool.namaAlat}`"
            class="absolute inset-0 h-full w-full object-cover"
          />
          <div v-else class="absolute inset-0 flex items-center justify-center">
          <svg
            width="84"
            height="84"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            class="text-slate-600/70 dark:text-slate-300/60"
            aria-hidden="true"
          >
            <path
              d="M8 21h8c3.771 0 5.657 0 6.828-1.172C24 18.657 24 16.771 24 13V11c0-3.771 0-5.657-1.172-6.828C21.657 3 19.771 3 16 3H8C4.229 3 2.343 3 .172 4.172 0 5.343 0 7.229 0 11v2c0 3.771 0 5.657.172 6.828C1.343 21 3.229 21 7 21h1Z"
              stroke="currentColor"
              stroke-width="1.3"
              stroke-linecap="round"
            />
            <path
              d="M4.5 16.5 8.2 12.8c.4-.4 1.05-.4 1.45 0l2.1 2.1c.4.4 1.05.4 1.45 0L19.5 8.6"
              stroke="currentColor"
              stroke-width="1.3"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M16.5 8.5h3v3"
              stroke="currentColor"
              stroke-width="1.3"
              stroke-linecap="round"
              stroke-linejoin="round"
            />
            <path
              d="M16.9 7.1c.4-.4 1.1-.4 1.5 0s.4 1.1 0 1.5-1.1.4-1.5 0-.4-1.1 0-1.5Z"
              fill="currentColor"
              opacity="0.85"
            />
          </svg>
          </div>
        </div>
      </div>

      <div class="relative bg-gradient-to-r from-lime-700 to-lime-600 px-5 pb-5 pt-5">
        <svg
          class="pointer-events-none absolute -top-9 left-0 h-10 w-full"
          viewBox="0 0 500 50"
          preserveAspectRatio="none"
          aria-hidden="true"
        >
          <path d="M0,30 C120,62 170,0 280,25 C360,40 420,12 500,30 L500,50 L0,50 Z" class="fill-lime-700" />
          <path d="M0,35 C120,62 170,10 280,30 C360,45 420,20 500,35 L500,50 L0,50 Z" class="fill-lime-600/70" />
        </svg>

        <h3 class="truncate text-lg font-extrabold text-slate-950" :title="tool.namaAlat">
          {{ tool.namaAlat }}
        </h3>

        <div class="mt-4 grid grid-cols-2 gap-4">
          <button
            type="button"
            class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-emerald-600/50"
            :disabled="borrowDisabled"
            @click="$emit('borrow', tool)"
          >
            Pinjam
          </button>
          <button
            type="button"
            class="inline-flex items-center justify-center rounded-2xl bg-slate-200 px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-300 dark:bg-slate-200/85 dark:text-slate-900 dark:hover:bg-slate-100"
            @click="$emit('detail', tool)"
          >
            Detail
          </button>
        </div>
      </div>
    </div>
  </article>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  tool: {
    type: Object,
    required: true,
  },
})

defineEmits(['borrow', 'detail'])

const borrowDisabled = computed(() => props.tool?.status !== 'Tersedia' || Number(props.tool?.stok || 0) <= 0)
</script>
