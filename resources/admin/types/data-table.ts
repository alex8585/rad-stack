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
export interface TagRowFormType {
  name?: string | null
  order_number?: string | null
  id?: number | null
}

export interface ActiveObjl {
  label: string
  value: number
}

export interface UserRowFormType {
  name?: string | null
  email?: string | null
  id?: number | null
  password?: string | null
  active?: ActiveObjl | null
  role?: string | null
}
export interface OptionType {
  label: string
  value: number
}
export interface TagType {
  name: string
  id: number
}
export interface PortfolioRowFormType {
  name?: string | null
  url?: string | null
  order_number: string
  id?: number | null
  files: File[]
  tags: OptionType[]
}
