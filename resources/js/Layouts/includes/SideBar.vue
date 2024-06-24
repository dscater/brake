<script setup>
import { useMenu } from "@/composables/useMenu";
import { onMounted, ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { useUser } from "@/composables/useUser";
const { oUser } = useUser();

// data
const {
    mobile,
    drawer,
    rail,
    width,
    menu_open,
    setMenuOpen,
    cambiarUrl,
    toggleDrawer,
} = useMenu();

const user_logeado = ref({
    permisos: [],
});

const submenus = {
    "reportes.usuarios": "Reportes",
    "reportes.ingresos": "Reportes",
    "reportes.egresos": "Reportes",
    "reportes.presupuestos": "Reportes",
    "reportes.ganancias": "Reportes",
    "reportes.movimientos": "Reportes",
};

const route_current = ref("");

router.on("navigate", (event) => {
    route_current.value = route().current();
    if (mobile.value) {
        toggleDrawer(false);
    }
});

const { props: props_page } = usePage();

onMounted(() => {
    let route_actual = route().current();
    // buscar en submenus y abrirlo si uno de sus elementos esta activo
    setMenuOpen([]);
    if (submenus[route_actual]) {
        setMenuOpen([submenus[route_actual]]);
    }

    if (props_page.auth) {
        user_logeado.value = props_page.auth?.user;
    }

    setTimeout(() => {
        scrollActive();
    }, 300);
});

const scrollActive = () => {
    let sidebar = document.querySelector("#sidebar");
    let menu = null;
    let activeChild = null;
    if (sidebar) {
        menu = sidebar.querySelector(".v-navigation-drawer__content");
        activeChild = sidebar.querySelector(".active");
    }
    // Verifica si se encontró un hijo activo
    if (activeChild) {
        let offsetTop = activeChild.offsetTop - sidebar.offsetTop;
        setTimeout(() => {
            menu.scrollTo({
                top: offsetTop,
                behavior: "smooth", // desplazamiento suave
            });
        }, 500);
    }
};
</script>
<template>
    <v-navigation-drawer
        :permanent="!mobile"
        :temporary="mobile"
        v-model="drawer"
        class="border-0 elevation-2 __sidebar"
        :width="width"
        id="sidebar"
    >
        <v-sheet>
            <div
                class="w-100 d-flex flex-column align-center elevation-1 bg-principal pa-2 __info_usuario"
            >
                <v-avatar
                    class="mb-1"
                    color="blue-darken-4"
                    :size="`${!rail ? '64' : '32'}`"
                >
                    <v-img
                        cover
                        v-if="oUser.url_foto"
                        :src="oUser.url_foto"
                    ></v-img>
                    <span v-else class="text-h5">
                        {{ oUser.iniciales_nombre }}
                    </span>
                </v-avatar>
                <div
                    v-show="!rail"
                    class="text-caption font-weight-bold text-white"
                >
                    {{ oUser.tipo }}
                </div>
                <div v-show="!rail" class="text-body text-white">
                    {{ oUser.full_name }}
                </div>
            </div>
        </v-sheet>

        <v-list class="px-0" v-model:opened="menu_open">
            <v-list-item class="text-caption bg-grey-lighten-3">
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else class="font-weight-bold d-block text-center"
                    >INICIO</span
                ></v-list-item
            >
            <v-list-item
                class="mx-3"
                prepend-icon="mdi-home-outline"
                :class="[
                    route_current == 'inicio' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                @click="cambiarUrl(route('inicio'))"
                link
            >
                <v-list-item-title>Inicio</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Inicio</v-tooltip
                >
            </v-list-item>
            <v-list-item
                class="text-caption pa-0 px-5 bg-grey-lighten-3"
                v-if="
                    oUser.permisos.includes('usuarios.index') ||
                    oUser.permisos.includes('categorias.index') ||
                    oUser.permisos.includes('conceptos.index') ||
                    oUser.permisos.includes('ingresos.index') ||
                    oUser.permisos.includes('egresos.index')
                "
            >
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else class="font-weight-bold d-block text-center"
                    >ADMINISTRACIÓN</span
                ></v-list-item
            >
            <v-list-item
                :class="[
                    route_current == 'categorias.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('categorias.index')"
                prepend-icon="mdi-playlist-edit"
                @click="cambiarUrl(route('categorias.index'))"
                link
            >
                <v-list-item-title>Categorías</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Categorías</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'conceptos.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('conceptos.index')"
                prepend-icon="mdi-tag-multiple"
                @click="cambiarUrl(route('conceptos.index'))"
                link
            >
                <v-list-item-title>Conceptos de Movimientos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Conceptos de Movimientos</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'ingresos.index' ||
                    route_current == 'ingresos.create' ||
                    route_current == 'ingresos.edit' ||
                    route_current == 'ingresos.show'
                        ? 'active'
                        : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('ingresos.index')"
                prepend-icon="mdi-clipboard-arrow-left"
                @click="cambiarUrl(route('ingresos.index'))"
                link
            >
                <v-list-item-title>Ingresos Económicos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Ingresos Económicos</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'egresos.index' ||
                    route_current == 'egresos.create' ||
                    route_current == 'egresos.edit' ||
                    route_current == 'egresos.show'
                        ? 'active'
                        : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('egresos.index')"
                prepend-icon="mdi-clipboard-arrow-right"
                @click="cambiarUrl(route('egresos.index'))"
                link
            >
                <v-list-item-title>Egresos Económicos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Egresos Económicos</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'usuarios.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('usuarios.index')"
                prepend-icon="mdi-account-group"
                @click="cambiarUrl(route('usuarios.index'))"
                link
            >
                <v-list-item-title>Usuarios</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Usuarios</v-tooltip
                >
            </v-list-item>
            <v-list-item
                class="text-caption bg-grey-lighten-3"
                v-if="oUser.permisos.includes('reportes.usuarios')"
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else class="font-weight-bold d-block text-center"
                    >REPORTES</span
                ></v-list-item
            >
            <!-- SUBGROUP -->
            <v-list-group
                value="Reportes"
                class="mx-3"
                v-if="
                    oUser.permisos.includes('reportes.usuarios') ||
                    oUser.permisos.includes('reportes.ingresos')||
                    oUser.permisos.includes('reportes.egresos')||
                    oUser.permisos.includes('reportes.presupuestos')||
                    oUser.permisos.includes('reportes.ganancias')||
                    oUser.permisos.includes('reportes.movimientos')
                "
            >
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-file-document-multiple"
                        title="Reportes"
                        :class="[
                            route_current == 'reporutes.usuarios' ||
                            route_current == 'reportes.ingresos'||
                            route_current == 'reportes.egresos'||
                            route_current == 'reportes.presupuestos'||
                            route_current == 'reportes.ganancias'||
                            route_current == 'reportes.movimientos'
                                ? 'active'
                                : '',
                        ]"
                    >
                        <v-tooltip
                            v-if="rail && !mobile"
                            color="white"
                            activator="parent"
                            location="end"
                            >Reportes</v-tooltip
                        ></v-list-item
                    >
                </template>
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.usuarios')"
                    prepend-icon="mdi-chevron-right"
                    title="Usuarios"
                    :class="[
                        route_current == 'reportes.usuarios' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.usuarios'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Usuarios</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.ingresos')"
                    prepend-icon="mdi-chevron-right"
                    title="Ingresos Económicos"
                    :class="[
                        route_current == 'reportes.ingresos' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.ingresos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Ingresos Económicos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.egresos')"
                    prepend-icon="mdi-chevron-right"
                    title="Egresos Económicos"
                    :class="[
                        route_current == 'reportes.egresos' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.egresos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Egresos Económicos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.presupuestos')"
                    prepend-icon="mdi-chevron-right"
                    title="Presupuestos"
                    :class="[
                        route_current == 'reportes.presupuestos' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.presupuestos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Presupuestos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.ganancias')"
                    prepend-icon="mdi-chevron-right"
                    title="Ganancias Netas"
                    :class="[
                        route_current == 'reportes.ganancias' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.ganancias'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Ganancias Netas</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.movimientos')"
                    prepend-icon="mdi-chevron-right"
                    title="Movimientos de I. y E. Económicos"
                    :class="[
                        route_current == 'reportes.movimientos' ? 'active' : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.movimientos'))"
                    link
                >
                    <v-tooltip
                        color="white"
                        activator="parent"
                        location="end"
                        >Movimientos de Ingresos y Egresos Económicos</v-tooltip
                    ></v-list-item
                >
            </v-list-group>
            <v-list-item class="text-caption bg-grey-lighten-3"
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else class="font-weight-bold d-block text-center"
                    >OTROS</span
                ></v-list-item
            >
            <v-list-item
                v-if="oUser.permisos.includes('configuracions.index')"
                prepend-icon="mdi-cog-outline"
                class="mx-3"
                :class="[
                    route_current == 'configuracions.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                @click="cambiarUrl(route('configuracions.index'))"
                link
            >
                <v-list-item-title>Configuración</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Configuración</v-tooltip
                >
            </v-list-item>
            <v-list-item
                prepend-icon="mdi-account-circle"
                class="mx-3"
                :class="[
                    route_current == 'profile.edit' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                @click="cambiarUrl(route('profile.edit'))"
                link
            >
                <v-list-item-title>Perfil</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Perfil</v-tooltip
                >
            </v-list-item>
            <v-list-item
                prepend-icon="mdi-logout"
                class="mx-3"
                @click="cambiarUrl(route('logout'), 'post')"
                link
            >
                <v-list-item-title>Salir</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Salir</v-tooltip
                >
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>
<style scoped></style>
