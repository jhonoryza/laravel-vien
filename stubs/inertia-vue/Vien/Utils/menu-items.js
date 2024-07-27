import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import {
  IconHome,
  IconSettings,
  IconUsers,
} from "@tabler/icons-vue";

export function useMenuItems() {
    /**
     * id harus unique
     * semua attribut wajib diisi
     */
    const menuItems = ref([
        {
            id: 1,
            title: "Dashboard",
            routeName: "dashboard",
            component: "Dashboard",
            icon: IconHome,
        },
        {
            id: 2,
            title: "Core",
            children: [
            {
                id: 21,
                title: "User",
                routeName: "users.index",
                component: "User/Index",
                icon: IconUsers,
            },
            {
                id: 22,
                title: "Setting",
                routeName: "settings.index",
                component: "Setting/Index",
                icon: IconSettings,
            },
            ],
        },
        {
            id: 3,
            title: usePage().props.auth.user.name,
            class: "xl:hidden",
            children: [
            {
                id: 31,
                title: "Profile",
                routeName: "profile.edit",
                component: "Profile/Edit",
                icon: null,
                class: "xl:hidden",
            },
            {
                id: 32,
                title: "Logout",
                routeName: "logout",
                component: "",
                icon: null,
                method: "post",
                class: "xl:hidden",
            },
            ],
        }
    ]);
    return {
        menuItems
    };
}