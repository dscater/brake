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
    "reportes.productos": "Reportes",
    "reportes.ingreso_productos": "Reportes",
    "reportes.salida_productos": "Reportes",
    "reportes.inventario_productos": "Reportes",
    "reportes.kardex_productos": "Reportes",
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
        class="border-0 elevation-2 __sidebar bg-primary"
        :width="width"
        id="sidebar"
    >
        <v-sheet>
            <div
                class="w-100 d-flex flex-column align-center elevation-1 bg-primary pa-2 __info_usuario"
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
                <div v-show="!rail" class="text-caption font-weight-bold">
                    {{ oUser.tipo }}
                </div>
                <div v-show="!rail" class="text-body">
                    {{ oUser.full_name }}
                </div>
            </div>
        </v-sheet>

        <v-list class="mt-1 px-0" v-model:opened="menu_open">
            <v-list-item class="text-caption bg-blue-darken-4">
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>INICIO</span></v-list-item
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
                class="text-caption bg-blue-darken-4 pa-0 px-5"
                v-if="
                    oUser.permisos.includes('usuarios.index') ||
                    oUser.permisos.includes('proveedors.index') ||
                    oUser.permisos.includes('categorias.index') ||
                    oUser.permisos.includes('tipo_productos.index') ||
                    oUser.permisos.includes('productos.geolocalizacion') ||
                    oUser.permisos.includes('tipo_ingresos.index') ||
                    oUser.permisos.includes('ingresos.index') ||
                    oUser.permisos.includes('tipo_salidas.index') ||
                    oUser.permisos.includes('salidas_productos.index') ||
                    oUser.permisos.includes('notificacions.index')
                "
            >
                <span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>ADMINISTRACIÓN</span></v-list-item
            >
            <v-list-item
                :class="[
                    route_current == 'proveedors.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('proveedors.index')"
                prepend-icon="mdi-account-badge"
                @click="cambiarUrl(route('proveedors.index'))"
                link
            >
                <v-list-item-title>Proveedores</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Proveedores</v-tooltip
                >
            </v-list-item>
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
                    route_current == 'tipo_productos.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('tipo_productos.index')"
                prepend-icon="mdi-tag-multiple"
                @click="cambiarUrl(route('tipo_productos.index'))"
                link
            >
                <v-list-item-title>Tipo de Productos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Tipo de Productos</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'productos.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('productos.index')"
                prepend-icon="mdi-clipboard-list"
                @click="cambiarUrl(route('productos.index'))"
                link
            >
                <v-list-item-title>Productos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Productos</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'tipo_ingresos.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('tipo_ingresos.index')"
                prepend-icon="mdi-view-list"
                @click="cambiarUrl(route('tipo_ingresos.index'))"
                link
            >
                <v-list-item-title>Tipo de Ingresos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Tipo de Ingresos</v-tooltip
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
                <v-list-item-title>Ingreso de Productos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Ingreso de Productos</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'tipo_salidas.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('tipo_salidas.index')"
                prepend-icon="mdi-view-list"
                @click="cambiarUrl(route('tipo_salidas.index'))"
                link
            >
                <v-list-item-title>Tipo de Salidas</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Tipo de Salidas</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'salidas.index' ||
                    route_current == 'salidas.create' ||
                    route_current == 'salidas.edit' ||
                    route_current == 'salidas.show'
                        ? 'active'
                        : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('salidas.index')"
                prepend-icon="mdi-clipboard-arrow-right"
                @click="cambiarUrl(route('salidas.index'))"
                link
            >
                <v-list-item-title>Salida de Productos</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Salida de Productos</v-tooltip
                >
            </v-list-item>
            <v-list-item
                :class="[
                    route_current == 'notificacions.index' ? 'active' : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('notificacions.index')"
                prepend-icon="mdi-bell"
                @click="cambiarUrl(route('notificacions.index'))"
                link
            >
                <v-list-item-title>Notificaciones</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Notificaciones</v-tooltip
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
                class="text-caption bg-blue-darken-4"
                v-if="
                    oUser.permisos.includes('reportes.usuarios') ||
                    oUser.permisos.includes('reportes.productos') ||
                    oUser.permisos.includes('reportes.ingreso_productos') ||
                    oUser.permisos.includes('reportes.salida_productos') ||
                    oUser.permisos.includes('reportes.inventario_productos') ||
                    oUser.permisos.includes('reportes.kardex_productos') ||
                    oUser.permisos.includes('reportes.analisis_almacen')
                "
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>REPORTES</span></v-list-item
            >
            <!-- SUBGROUP -->
            <v-list-group
                value="Reportes"
                class="mx-3"
                v-if="
                    oUser.permisos.includes('reportes.usuarios') ||
                    oUser.permisos.includes('reportes.productos') ||
                    oUser.permisos.includes('reportes.ingreso_productos') ||
                    oUser.permisos.includes('reportes.salida_productos') ||
                    oUser.permisos.includes('reportes.inventario_productos') ||
                    oUser.permisos.includes('reportes.kardex_productos')
                "
            >
                <template v-slot:activator="{ props }">
                    <v-list-item
                        v-bind="props"
                        prepend-icon="mdi-file-document-multiple"
                        title="Reportes"
                        :class="[
                            route_current == 'reporutes.usuarios' ||
                            route_current == 'reportes.productos' ||
                            route_current == 'reportes.ingreso_productos' ||
                            route_current == 'reportes.salida_productos' ||
                            route_current == 'reportes.inventario_productos' ||
                            route_current == 'reportes.kardex_productos'
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
                    v-if="oUser.permisos.includes('reportes.productos')"
                    prepend-icon="mdi-chevron-right"
                    title="Lista de Productos"
                    :class="[
                        route_current == 'reportes.productos' ? 'active' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.productos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Lista de Productos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.ingreso_productos')"
                    prepend-icon="mdi-chevron-right"
                    title="Ingreso de Productos"
                    :class="[
                        route_current == 'reportes.ingreso_productos'
                            ? 'active'
                            : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.ingreso_productos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Ingreso de Productos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.salida_productos')"
                    prepend-icon="mdi-chevron-right"
                    title="Salida de Productos"
                    :class="[
                        route_current == 'reportes.salida_productos'
                            ? 'active'
                            : '',
                        drawer ? 'px-3' : '',
                    ]"
                    @click="cambiarUrl(route('reportes.salida_productos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Salida de Productos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="
                        oUser.permisos.includes('reportes.inventario_productos')
                    "
                    prepend-icon="mdi-chevron-right"
                    title="Inventario de Productos"
                    :class="[
                        route_current == 'reportes.inventario_productos'
                            ? 'active'
                            : '',
                    ]"
                    @click="cambiarUrl(route('reportes.inventario_productos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Inventario de Productos</v-tooltip
                    ></v-list-item
                >
                <v-list-item
                    v-if="oUser.permisos.includes('reportes.kardex_productos')"
                    prepend-icon="mdi-chevron-right"
                    title="Kardex de Productos"
                    :class="[
                        route_current == 'reportes.kardex_productos'
                            ? 'active'
                            : '',
                    ]"
                    @click="cambiarUrl(route('reportes.kardex_productos'))"
                    link
                >
                    <v-tooltip
                        v-if="rail && !mobile"
                        color="white"
                        activator="parent"
                        location="end"
                        >Kardex de Productos</v-tooltip
                    ></v-list-item
                >
            </v-list-group>
            <v-list-item
                :class="[
                    route_current == 'reportes.analisis_almacen'
                        ? 'active'
                        : '',
                    drawer ? 'px-3' : '',
                ]"
                class="mx-3"
                v-if="oUser.permisos.includes('reportes.analisis_almacen')"
                prepend-icon="mdi-chart-bar"
                @click="cambiarUrl(route('reportes.analisis_almacen'))"
                link
            >
                <v-list-item-title>Análisis de almacén</v-list-item-title>
                <v-tooltip
                    v-if="rail && !mobile"
                    color="white"
                    activator="parent"
                    location="end"
                    >Análisis de almacén</v-tooltip
                >
            </v-list-item>
            <v-list-item class="text-caption bg-blue-darken-4"
                ><span v-if="rail && !mobile" class="text-center d-block"
                    ><v-icon>mdi-dots-horizontal</v-icon></span
                >
                <span v-else>OTROS</span></v-list-item
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
