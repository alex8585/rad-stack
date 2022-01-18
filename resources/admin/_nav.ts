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
    newicon: 'record_voice_over',
    text: __('Dashboard'),
  },
  {
    href: route('admin.arts'),
    active: () => route().current('admin.arts'),
    icon: 'chart-bar',
    newicon: 'school',
    text: __('Arts'),
  },
  // { title: __('Content Managment') },
  {
    href: route('admin.posts'),
    active: () =>
      route().current('admin.posts') || route().current('admin.posts.*'),
    icon: 'newspaper',

    newicon: 'code',
    text: __('Posts'),
  },
  // { title: __('Access Managment') },
  {
    href: route('admin.users'),
    active: () =>
      route().current('admin.users') || route().current('admin.users.*'),
    icon: 'users',
    newicon: 'chat',
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
