export const categorySeed = []

export const userSeed = []

export const toolSeed = []

export function getCategoryOptions() {
  if (typeof window === 'undefined') {
    return []
  }

  const storedCategories = window.localStorage.getItem('admin-management-categories')

  if (storedCategories) {
    try {
      const parsed = JSON.parse(storedCategories)

      return parsed
        .filter((item) => item.status === 'Aktif')
        .map((item) => item.namaKategori)
    } catch (error) {
      window.localStorage.removeItem('admin-management-categories')
    }
  }

  return []
}
