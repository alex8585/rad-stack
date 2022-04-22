import { computed } from 'vue'
export function usePages(props) {
  function getPageCount(totalCount, pageSize) {
    const pagesCount =
      totalCount < pageSize ? 1 : Math.ceil(totalCount / pageSize)

    return pagesCount
  }

  const pagesCount = computed(() => getPageCount(props.total, props.perPage))

  return pagesCount
}
