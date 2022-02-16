import { __ } from 'matice'
import route from 'ziggy-js'

interface NavTitle {
  title: string
}
interface NavLink {
  href: string
  active: () => boolean
  icon: string
  newicon?: string
  text: string
}

const mainNav: (NavLink | NavTitle)[] = [
  {
    href: route('admin.dashboard'),
    active: () => route().current('admin.dashboard'),
    icon: 'chart-bar',
    newicon: 'dashboard',
    text: __('Dashboard'),
  },
  {
    href: route('admin.arts'),
    active: () => route().current('admin.arts'),
    icon: 'chart-bar',
    newicon: 'collections',
    text: __('Arts'),
  },
  // { title: __('Content Managment') },
  {
    href: route('admin.portfolios'),
    active: () =>
      route().current('admin.portfolios') ||
      route().current('admin.portfolios.*'),
    icon: 'portrait',

    newicon: 'library_books',
    text: __('Portfolios'),
  },

  {
    href: route('admin.tags'),
    active: () =>
      route().current('admin.tags') || route().current('admin.tags.*'),
    icon: 'newspaper',

    newicon: 'local_offer',
    text: __('Tags'),
  },
  // { title: __('Access Managment') },
  {
    href: route('admin.users'),
    active: () =>
      route().current('admin.users') || route().current('admin.users.*'),
    icon: 'users',
    newicon: 'people',
    text: __('Users'),
  },
]

const isTitle = (a: NavTitle | NavLink): a is NavTitle => {
  return (a as NavTitle).title !== undefined
}
const isLink = (a: NavTitle | NavLink): a is NavLink => {
  return (a as NavLink).href !== undefined
}

export { NavLink, NavTitle, isTitle, isLink, mainNav }
