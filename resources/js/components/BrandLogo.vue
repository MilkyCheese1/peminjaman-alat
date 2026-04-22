<template>
  <div :class="containerClass">
    <img
      :src="logoSrc"
      :alt="`${brandName} logo`"
      :class="imageClass"
    >
    <div v-if="showText" :class="textClass">
      <h1 :class="titleClass">{{ brandName }}</h1>
      <p v-if="subtitle" :class="subtitleClass">{{ subtitle }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  brandName: {
    type: String,
    default: 'TrustEquip.id',
  },
  subtitle: {
    type: String,
    default: '',
  },
  layout: {
    type: String,
    default: 'horizontal',
    validator: (value) => ['horizontal', 'stacked'].includes(value),
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
  showText: {
    type: Boolean,
    default: true,
  },
})

const logoSrc = '/trustequip-logo.png'

const containerClass = computed(() => {
  const base = 'flex'
  const layoutClasses = props.layout === 'stacked'
    ? 'flex-col items-center text-center'
    : 'items-center gap-3'

  return [base, layoutClasses].join(' ')
})

const imageClass = computed(() => {
  const sizeClasses = {
    sm: 'h-10 w-10 rounded-2xl',
    md: 'h-14 w-14 rounded-3xl',
    lg: 'h-20 w-20 rounded-[1.5rem]',
  }

  return [
    'object-cover flex-shrink-0 shadow-lg shadow-slate-950/10 ring-1 ring-slate-200/70 dark:ring-white/10 bg-transparent',
    sizeClasses[props.size] || sizeClasses.md,
  ].join(' ')
})

const textClass = computed(() => {
  return props.layout === 'stacked'
    ? 'space-y-1'
    : 'space-y-0.5'
})

const titleClass = computed(() => {
  const sizeClasses = {
    sm: 'text-lg font-semibold tracking-tight',
    md: 'text-xl font-semibold tracking-tight',
    lg: 'text-2xl font-bold tracking-tight',
  }

  return [sizeClasses[props.size] || sizeClasses.md, 'text-slate-900 dark:text-white'].join(' ')
})

const subtitleClass = computed(() => 'text-xs uppercase tracking-[0.28em] text-slate-400')
</script>
