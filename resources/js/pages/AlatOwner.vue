<template>
  <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col lg:flex-row">
    <SidebarOwner />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Daftar Alat</h1>
          <p class="text-slate-700 dark:text-slate-300">Pantau daftar alat yang tercatat di sistem.</p>
        </div>

        <section>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <ToolCard v-for="tool in displayedTools" :key="tool.id" :tool="tool" @detail="handleDetail" @borrow="handleBorrow" />
          </div>
        </section>

        <BorrowToolModal :open="isBorrowModalOpen" :tool="selectedTool" @close="closeBorrowModal" @submit="submitBorrowRequest" />
      </main>
    </div>
  </div>
</template>

<script>
import SidebarOwner from '../components/layout/SidebarOwner.vue'
import Navbar from '../components/layout/Navbar.vue'
import ToolCard from '../components/tools/ToolCard.vue'
import BorrowToolModal from '../components/tools/BorrowToolModal.vue'
import { apiRequest } from '../lib/api'

export default {
  name: 'AlatOwner',
  components: {
    SidebarOwner,
    Navbar,
    ToolCard,
    BorrowToolModal,
  },
  data() {
    return {
      tools: [],
      isBorrowModalOpen: false,
      selectedTool: null,
    }
  },
  computed: {
    displayedTools() {
      return Array.isArray(this.tools) ? this.tools : []
    },
  },
  async created() {
    try {
      const data = await apiRequest('/api/tools')
      this.tools = Array.isArray(data) ? data : []
    } catch (error) {
      this.tools = []
    }
  },
  methods: {
    handleBorrow(tool) {
      this.selectedTool = tool ?? null
      this.isBorrowModalOpen = true
    },
    handleDetail(tool) {
      if (typeof window === 'undefined') {
        return
      }

      window.alert(`Detail: ${tool?.namaAlat ?? 'alat'}`)
    },
    closeBorrowModal() {
      this.isBorrowModalOpen = false
      this.selectedTool = null
    },
    submitBorrowRequest() {
      if (typeof window !== 'undefined') {
        window.alert('Pengajuan peminjaman dari owner belum diaktifkan.')
      }

      this.closeBorrowModal()
    },
  },
}
</script>

