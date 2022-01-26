export interface Column {
  field: string
  source?: string
  label?: string
  width?: number | string
  numeric?: boolean
  centered?: boolean
  sortable?: boolean
  searchable?: boolean
  type?: string
  props?: any
  filterType?: string
}

export interface Col {
  name: string
  align?: 'right' | 'left' | 'center' | undefined
  required?: boolean
  label: string
  field: any
  sortable: boolean
  format?: any
}

export interface ArtRowFormType {
  files: File[]
  title?: string | null
  description: string
  id?: number | null
}
