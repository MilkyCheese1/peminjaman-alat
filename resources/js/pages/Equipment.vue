<script setup lang="ts">
import { ref, onMounted } from "vue"
import { Card, CardContent } from "@/components/ui"
import { Package, X, Grid, List } from "lucide-vue-next"
import axios from "axios"

interface Category {
  id_kategori: number
  nama_kategori: string
}

interface Equipment {
  id_equipment: number
  nama_alat: string
  gambar?: string
  deskripsi: string
  category?: Category
  available_quantity: number
  total_stok: number
  kondisi: string
  fine_per_day: number
}

const loading = ref(false)
const equipment = ref<Equipment[]>([])
const error = ref("")
const showDetailModal = ref(false)
const selectedEquipment = ref<Equipment | null>(null)
const viewMode = ref<'card' | 'table'>('card')

onMounted(async () => {
  await loadEquipment()
})

const loadEquipment = async () => {
  try {
    loading.value = true
    const response = await axios.get("/api/equipment")
    equipment.value = response.data.data || []
  } catch (err: any) {
    error.value = err.message
    console.error("Failed to load equipment:", err)
  } finally {
    loading.value = false
  }
}

const openDetail = (item: any) => {
  selectedEquipment.value = item
  showDetailModal.value = true
}

const closeDetail = () => {
  showDetailModal.value = false
  selectedEquipment.value = null
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-foreground flex items-center gap-2">
        <Package :size="32" />
        Manajemen Alat
      </h1>
      <p class="text-muted-foreground mt-1">Kelola daftar alat yang tersedia di sistem</p>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="bg-destructive/10 border border-destructive text-destructive px-4 py-3 rounded-lg mb-6">
      {{ error }}
    </div>

    <!-- View Toggle Buttons -->
    <div class="flex gap-2 mb-6">
      <button
        @click="viewMode = 'card'"
        :class="[
          'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors',
          viewMode === 'card'
            ? 'bg-primary text-primary-foreground'
            : 'bg-secondary text-foreground hover:bg-secondary/80'
        ]"
      >
        <Grid :size="20" />
        Card
      </button>
      <button
        @click="viewMode = 'table'"
        :class="[
          'flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors',
          viewMode === 'table'
            ? 'bg-primary text-primary-foreground'
            : 'bg-secondary text-foreground hover:bg-secondary/80'
        ]"
      >
        <List :size="20" />
        Tabel
      </button>
    </div>

    <!-- Equipment List -->
    <div v-if="loading" class="text-center py-12">
      <p class="text-muted-foreground">Memuat data alat...</p>
    </div>

    <!-- Card View -->
    <div v-else-if="viewMode === 'card'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <Card
        v-for="item in equipment"
        :key="item.id_equipment"
        class="flex flex-col overflow-hidden hover:shadow-lg transition-shadow"
      >
        <!-- Image Section -->
        <div class="w-full h-48 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center overflow-hidden">
          <img
            v-if="item.gambar"
            :src="item.gambar"
            :alt="item.nama_alat"
            class="w-full h-full object-cover"
          />
          <Package v-else :size="64" class="text-muted-foreground opacity-50" />
        </div>

        <!-- Content Section -->
        <CardContent class="flex-grow space-y-3 pt-4">
          <div>
            <p class="text-lg font-semibold line-clamp-2">{{ item.nama_alat }}</p>
            <p class="text-xs text-muted-foreground mt-1">{{ item.category?.nama_kategori }}</p>
          </div>

          <p class="text-sm text-muted-foreground line-clamp-3">{{ item.deskripsi }}</p>

          <!-- Quick Status Badge -->
          <div class="flex gap-2 items-center pt-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="item.available_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
              {{ item.available_quantity > 0 ? "Tersedia" : "Habis" }}
            </span>
            <span class="text-xs text-muted-foreground">{{ item.available_quantity }}/{{ item.total_stok }}</span>
          </div>
        </CardContent>

        <!-- Action Buttons -->
        <div class="flex gap-2 p-4 border-t bg-card">
          <button
            class="flex-1 px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium"
            :disabled="item.available_quantity === 0"
            :class="item.available_quantity === 0 ? 'opacity-50 cursor-not-allowed' : ''"
          >
            Pinjam
          </button>
          <button
            @click="openDetail(item)"
            class="flex-1 px-3 py-2 bg-slate-200 text-foreground rounded-md hover:bg-slate-300 transition-colors text-sm font-medium"
          >
            Detail
          </button>
        </div>
      </Card>
    </div>

    <!-- Table View -->
    <div v-else-if="viewMode === 'table'" class="overflow-x-auto border rounded-lg">
      <table class="w-full">
        <thead class="bg-muted">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold">Nama Alat</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Kategori</th>
            <th class="px-6 py-3 text-center text-sm font-semibold">Stok</th>
            <th class="px-6 py-3 text-center text-sm font-semibold">Tersedia</th>
            <th class="px-6 py-3 text-left text-sm font-semibold">Kondisi</th>
            <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="item in equipment" :key="item.id_equipment" class="hover:bg-muted/50">
            <td class="px-6 py-4 text-sm">{{ item.nama_alat }}</td>
            <td class="px-6 py-4 text-sm text-muted-foreground">{{ item.category?.nama_kategori }}</td>
            <td class="px-6 py-4 text-sm text-center font-medium">{{ item.total_stok }}</td>
            <td class="px-6 py-4 text-sm text-center">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="item.available_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                {{ item.available_quantity }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm capitalize">{{ item.kondisi }}</td>
            <td class="px-6 py-4 text-sm text-center">
              <div class="flex gap-2 justify-center">
                <button
                  class="px-3 py-1 bg-green-600 text-white rounded text-xs hover:bg-green-700 transition-colors"
                  :disabled="item.available_quantity === 0"
                  :class="item.available_quantity === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                >
                  Pinjam
                </button>
                <button
                  @click="openDetail(item)"
                  class="px-3 py-1 bg-slate-200 text-foreground rounded text-xs hover:bg-slate-300 transition-colors"
                >
                  Detail
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Empty State -->
    <div v-if="!loading && equipment.length === 0" class="text-center py-12">
      <Package class="mx-auto mb-4 text-muted-foreground" :size="48" />
      <p class="text-muted-foreground">Tidak ada data alat</p>
    </div>

    <!-- Detail Modal -->
    <div
      v-if="showDetailModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
      @click.self="closeDetail"
    >
      <div class="bg-card rounded-lg shadow-lg w-full max-w-md mx-4">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-border">
          <h2 class="text-xl font-bold text-foreground">Detail Alat</h2>
          <button
            @click="closeDetail"
            class="p-1 hover:bg-secondary rounded-md transition-colors"
          >
            <X :size="20" class="text-foreground" />
          </button>
        </div>

        <!-- Modal Content -->
        <div v-if="selectedEquipment" class="p-6 space-y-4">
          <!-- Image -->
          <div class="w-full h-40 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg flex items-center justify-center overflow-hidden">
            <img
              v-if="selectedEquipment.gambar"
              :src="selectedEquipment.gambar"
              :alt="selectedEquipment.nama_alat"
              class="w-full h-full object-cover"
            />
            <Package v-else :size="48" class="text-muted-foreground opacity-50" />
          </div>

          <!-- Title & Category -->
          <div>
            <h3 class="text-lg font-bold text-foreground">{{ selectedEquipment.nama_alat }}</h3>
            <p class="text-sm text-muted-foreground">{{ selectedEquipment.category?.nama_kategori }}</p>
          </div>

          <!-- Description -->
          <div>
            <p class="text-sm font-medium text-muted-foreground mb-1">Deskripsi</p>
            <p class="text-sm text-foreground">{{ selectedEquipment.deskripsi }}</p>
          </div>

          <!-- Info Grid -->
          <div class="grid grid-cols-2 gap-4 pt-4 border-t border-border">
            <div>
              <p class="text-xs font-medium text-muted-foreground mb-2">Stok</p>
              <p class="text-2xl font-bold text-foreground">{{ selectedEquipment.total_stok }}</p>
              <p class="text-xs text-muted-foreground mt-1">Total unit</p>
            </div>
            <div>
              <p class="text-xs font-medium text-muted-foreground mb-2">Tersedia</p>
              <p
                class="text-2xl font-bold"
                :class="selectedEquipment.available_quantity > 0 ? 'text-green-600' : 'text-red-600'"
              >
                {{ selectedEquipment.available_quantity }}
              </p>
              <p class="text-xs text-muted-foreground mt-1">Siap pinjam</p>
            </div>
            <div>
              <p class="text-xs font-medium text-muted-foreground mb-2">Kondisi</p>
              <p class="text-lg font-semibold text-foreground capitalize">{{ selectedEquipment.kondisi }}</p>
            </div>
            <div>
              <p class="text-xs font-medium text-muted-foreground mb-2">Denda/Hari</p>
              <p class="text-lg font-semibold text-foreground">
                Rp{{ selectedEquipment.fine_per_day.toLocaleString("id-ID") }}
              </p>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex gap-2 p-6 border-t border-border">
          <button
            @click="closeDetail"
            class="flex-1 px-4 py-2 bg-secondary text-foreground rounded-md hover:bg-secondary/80 transition-colors font-medium"
          >
            Tutup
          </button>
          <button
            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors font-medium"
            :disabled="selectedEquipment?.available_quantity === 0"
            :class="selectedEquipment?.available_quantity === 0 ? 'opacity-50 cursor-not-allowed' : ''"
          >
            Pinjam
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* No additional styles needed - using Tailwind CSS */
</style>
