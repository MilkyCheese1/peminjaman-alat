<template>
  <div v-if="open" class="fixed inset-0 z-[70] flex items-center justify-center p-4">
    <button type="button" class="absolute inset-0 bg-slate-950/80" aria-label="Tutup crop" @click="$emit('cancel')" />

    <div class="relative w-full max-w-3xl overflow-hidden rounded-[2rem] border border-cyan-200 bg-white shadow-2xl shadow-slate-950/60 dark:border-cyan-500/20 dark:bg-slate-950">
      <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-6 py-5 dark:border-slate-800">
        <div>
          <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Crop Foto</p>
          <h3 class="mt-1 text-xl font-extrabold text-slate-900 dark:text-white">Sesuaikan foto profil</h3>
        </div>
        <button
          type="button"
          class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-100 text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
          aria-label="Tutup crop"
          @click="$emit('cancel')"
        >
          ×
        </button>
      </div>

      <div class="grid gap-0 lg:grid-cols-[1.3fr_0.7fr]">
        <div class="border-b border-slate-200 p-6 dark:border-slate-800 lg:border-b-0 lg:border-r">
          <div
            ref="stage"
            class="relative mx-auto aspect-square w-full max-w-[420px] overflow-hidden rounded-[2rem] bg-slate-100 dark:bg-slate-900 select-none touch-none"
            @pointerdown="onPointerDown"
            @pointermove="onPointerMove"
            @pointerup="onPointerUp"
            @pointerleave="onPointerUp"
          >
            <img
              v-if="imageSrc"
              ref="image"
              :src="imageSrc"
              alt="Pratinjau crop"
              class="absolute left-1/2 top-1/2 max-w-none"
              :style="imageStyle"
              draggable="false"
            />
            <div class="pointer-events-none absolute inset-0 ring-1 ring-inset ring-white/25"></div>
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_center,transparent_58%,rgba(15,23,42,0.18)_100%)]"></div>
          </div>
          <p class="mt-4 text-center text-xs text-slate-500 dark:text-slate-400">
            Geser gambar untuk menyesuaikan posisi. Zoom akan menghasilkan foto profil yang lebih pas.
          </p>
        </div>

        <div class="p-6">
          <div class="space-y-6">
            <div>
              <label class="mb-2 block text-sm font-semibold text-slate-700 dark:text-slate-200">Zoom</label>
              <input
                v-model.number="scale"
                type="range"
                min="1"
                max="3"
                step="0.01"
                class="w-full accent-cyan-500"
              />
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900/40">
              <p class="text-sm font-bold text-slate-900 dark:text-white">Hasil kompres</p>
              <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-400">
                <li>Format: JPEG</li>
                <li>Kualitas: 82%</li>
                <li>Ukuran target: 512 x 512</li>
              </ul>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
              <button
                type="button"
                class="inline-flex items-center justify-center rounded-full bg-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-300 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                @click="$emit('cancel')"
              >
                Batal
              </button>
              <button
                type="button"
                class="inline-flex items-center justify-center rounded-full bg-cyan-500 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400 disabled:cursor-not-allowed disabled:opacity-60"
                :disabled="!imageSrc"
                @click="confirmCrop"
              >
                Simpan Foto
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const OUTPUT_SIZE = 512

export default {
  name: 'ImageCropModal',
  props: {
    open: {
      type: Boolean,
      default: false,
    },
    src: {
      type: String,
      default: '',
    },
  },
  emits: ['cancel', 'confirm'],
  data() {
    return {
      imageSrc: '',
      naturalWidth: 0,
      naturalHeight: 0,
      scale: 1,
      minScale: 1,
      offsetX: 0,
      offsetY: 0,
      dragging: false,
      pointerId: null,
      startX: 0,
      startY: 0,
      startOffsetX: 0,
      startOffsetY: 0,
    }
  },
  computed: {
    imageStyle() {
      if (!this.naturalWidth || !this.naturalHeight) {
        return {
          display: 'none',
        }
      }

      const renderedWidth = this.naturalWidth * this.scale
      const renderedHeight = this.naturalHeight * this.scale
      return {
        width: `${renderedWidth}px`,
        height: `${renderedHeight}px`,
        transform: `translate(-50%, -50%) translate(${this.offsetX}px, ${this.offsetY}px)`,
        cursor: this.dragging ? 'grabbing' : 'grab',
      }
    },
  },
  watch: {
    open(value) {
      if (value) {
        this.setupImage()
      }
    },
    src() {
      if (this.open) {
        this.setupImage()
      }
    },
    scale() {
      this.clampOffset()
    },
  },
  methods: {
    setupImage() {
      this.imageSrc = this.src || ''
      this.scale = 1
      this.minScale = 1
      this.offsetX = 0
      this.offsetY = 0

      if (!this.imageSrc) {
        return
      }

      const img = new Image()
      img.onload = () => {
        this.naturalWidth = img.naturalWidth || 0
        this.naturalHeight = img.naturalHeight || 0

        if (!this.naturalWidth || !this.naturalHeight) {
          return
        }

        const baseScale = Math.max(OUTPUT_SIZE / this.naturalWidth, OUTPUT_SIZE / this.naturalHeight)
        this.minScale = Math.max(1, baseScale)
        this.scale = this.minScale
        this.offsetX = 0
        this.offsetY = 0
      }
      img.src = this.imageSrc
    },
    clampOffset() {
      if (!this.naturalWidth || !this.naturalHeight) {
        return
      }

      const width = this.naturalWidth * this.scale
      const height = this.naturalHeight * this.scale
      const maxOffsetX = Math.max(0, (width - OUTPUT_SIZE) / 2)
      const maxOffsetY = Math.max(0, (height - OUTPUT_SIZE) / 2)
      this.offsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, this.offsetX))
      this.offsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, this.offsetY))
    },
    onPointerDown(event) {
      if (!this.imageSrc) return
      this.dragging = true
      this.pointerId = event.pointerId
      this.startX = event.clientX
      this.startY = event.clientY
      this.startOffsetX = this.offsetX
      this.startOffsetY = this.offsetY
      event.currentTarget?.setPointerCapture?.(event.pointerId)
    },
    onPointerMove(event) {
      if (!this.dragging || this.pointerId !== event.pointerId) return
      this.offsetX = this.startOffsetX + (event.clientX - this.startX)
      this.offsetY = this.startOffsetY + (event.clientY - this.startY)
      this.clampOffset()
    },
    onPointerUp(event) {
      if (this.pointerId !== null && event?.currentTarget?.hasPointerCapture?.(this.pointerId)) {
        event.currentTarget.releasePointerCapture(this.pointerId)
      }
      this.dragging = false
      this.pointerId = null
      this.clampOffset()
    },
    async confirmCrop() {
      if (!this.imageSrc || !this.naturalWidth || !this.naturalHeight) {
        return
      }

      const canvas = document.createElement('canvas')
      canvas.width = OUTPUT_SIZE
      canvas.height = OUTPUT_SIZE
      const ctx = canvas.getContext('2d')

      if (!ctx) {
        return
      }

      const img = new Image()
      img.crossOrigin = 'anonymous'

      img.onload = async () => {
        const scale = this.scale
        const drawWidth = this.naturalWidth * scale
        const drawHeight = this.naturalHeight * scale
        const x = (OUTPUT_SIZE - drawWidth) / 2 + this.offsetX
        const y = (OUTPUT_SIZE - drawHeight) / 2 + this.offsetY

        ctx.fillStyle = '#ffffff'
        ctx.fillRect(0, 0, OUTPUT_SIZE, OUTPUT_SIZE)
        ctx.imageSmoothingEnabled = true
        ctx.imageSmoothingQuality = 'high'
        ctx.drawImage(img, x, y, drawWidth, drawHeight)

        canvas.toBlob((blob) => {
          if (!blob) return
          const file = new File([blob], 'profile-photo.jpg', {
            type: 'image/jpeg',
            lastModified: Date.now(),
          })
          this.$emit('confirm', file)
        }, 'image/jpeg', 0.82)
      }

      img.src = this.imageSrc
    },
  },
}
</script>

<style scoped>
</style>
