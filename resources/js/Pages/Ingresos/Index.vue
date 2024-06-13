<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Ingresos",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { useMenu } from "@/composables/useMenu";
import { Head, usePage } from "@inertiajs/vue3";
import { useIngresos } from "@/composables/ingresos/useIngresos";
import { ref, onMounted } from "vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();
const { setLoading } = useApp();
const { props } = usePage();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getIngresosApi, deleteIngreso } = useIngresos();
const responseIngresos = ref([]);
const listIngresos = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
        sortable: false,
    },
    {
        title: "Proveedor",
        align: "start",
        sortable: false,
    },
    { title: "Tipo de Ingreso", align: "start", sortable: false },
    { title: "Precio", align: "start", sortable: false },
    { title: "Nro. Factura", align: "start", sortable: false },
    { title: "Descripción", align: "start", sortable: false },
    { title: "Fecha de Ingreso", align: "start", sortable: false },
    { title: "Fecha de Registro", align: "start", sortable: false },
    { title: "Más", align: "start", sortable: false },
    { title: "Acción", align: "end", sortable: false },
]);

const search = ref("");
const options = ref({
    page: 1,
    itemsPerPage: itemsPerPage,
    sortBy: "",
    sortOrder: "desc",
    search: "",
});

const loading = ref(true);
const totalItems = ref(0);
let setTimeOutLoadData = null;
const loadItems = async ({ page, itemsPerPage, sortBy }) => {
    loading.value = true;
    options.value.page = page;
    if (sortBy.length > 0) {
        options.value.sortBy = sortBy[0].key;
        options.value.sortOrder = sortBy[0].order;
    }
    options.value.search = search.value;

    clearInterval(setTimeOutLoadData);
    setTimeOutLoadData = setTimeout(async () => {
        responseIngresos.value = await getIngresosApi(options.value);
        listIngresos.value = responseIngresos.value.data;
        totalItems.value = parseInt(responseIngresos.value.total);
        loading.value = false;
    }, 300);
};
const recargaIngresos = async () => {
    loading.value = true;
    listIngresos.value = [];
    options.value.search = search.value;
    responseIngresos.value = await getIngresosApi(options.value);
    listIngresos.value = responseIngresos.value.data;
    totalItems.value = parseInt(responseIngresos.value.total);
    setTimeout(() => {
        loading.value = false;
    }, 300);
};

const editarIngreso = (item) => {
    cambiarUrl(route("ingresos.edit", item.id));
};

const verIngreso = (item) => {
    cambiarUrl(route("ingresos.show", item.id));
};

const pdf = (item) => {
    window.open(route("ingresos.pdf", item.id), "_blank");
};

const eliminarIngreso = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.nombre}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deleteIngreso(item.id);
            if (respuesta && respuesta.sw) {
                recargaIngresos();
            }
        }
    });
};
const verUbicación = async (item) => {};
</script>
<template>
    <Head title="Ingresos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    v-if="props.auth.user.permisos.includes('ingresos.create')"
                    color="primary"
                    prepend-icon="mdi-plus"
                    @click="cambiarUrl(route('ingresos.create'))"
                >
                    Agregar</v-btn
                >
            </v-col>
        </v-row>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card flat>
                    <v-card-title>
                        <v-row class="bg-primary d-flex align-center pa-3">
                            <v-col cols="12" sm="6" md="4"> Ingresos </v-col>
                            <v-col cols="12" sm="6" md="4" offset-md="4">
                                <v-text-field
                                    v-model="search"
                                    label="Buscar"
                                    append-inner-icon="mdi-magnify"
                                    variant="underlined"
                                    clearable
                                    hide-details
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-data-table-server
                            v-model:items-per-page="itemsPerPage"
                            :headers="!mobile ? headers : []"
                            :class="[mobile ? 'mobile' : '']"
                            :items-length="totalItems"
                            :items="listIngresos"
                            :loading="loading"
                            :search="search"
                            @update:options="loadItems"
                            height="auto"
                            no-data-text="No se encontrarón registros"
                            loading-text="Cargando..."
                            page-text="{0} - {1} de {2}"
                            items-per-page-text="Registros por página"
                            :items-per-page-options="[
                                { value: 10, title: '10' },
                                { value: 25, title: '25' },
                                { value: 50, title: '50' },
                                { value: 100, title: '100' },
                                {
                                    value: -1,
                                    title: 'Todos',
                                },
                            ]"
                        >
                            <template v-slot:item="{ item }">
                                <template v-if="!mobile">
                                    <tr>
                                        <td>{{ item.id }}</td>
                                        <td>
                                            {{ item.proveedor.razon_social }}
                                        </td>
                                        <td>{{ item.tipo_ingreso.nombre }}</td>
                                        <td>{{ item.precio }}</td>
                                        <td>{{ item.nro_factura }}</td>
                                        <td>{{ item.descripcion }}</td>
                                        <td>{{ item.fecha_ingreso_t }}</td>
                                        <td>{{ item.fecha_registro_t }}</td>
                                        <td>
                                            <v-btn
                                                :icon="
                                                    item.mas
                                                        ? 'mdi-chevron-down'
                                                        : 'mdi-chevron-left'
                                                "
                                                @click="item.mas = !item.mas"
                                            ></v-btn>
                                        </td>
                                        <td class="text-right" width="5%">
                                            <v-btn
                                                color="yellow-accent-2"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="pdf(item)"
                                                icon="mdi-file-outline"
                                            ></v-btn>
                                            <v-btn
                                                color="primary"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="verIngreso(item)"
                                                icon="mdi-eye-circle"
                                            ></v-btn>
                                            <!-- <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'ingresos.edit'
                                                    )
                                                "
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="editarIngreso(item)"
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'ingresos.destroy'
                                                    )
                                                "
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="eliminarIngreso(item)"
                                                icon="mdi-trash-can"
                                            ></v-btn> -->
                                        </td>
                                    </tr>
                                    <tr v-if="item.mas">
                                        <td
                                            :colspan="headers.length"
                                            class="py-5"
                                        >
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center"
                                                >
                                                    <v-table class="border">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    N°
                                                                </th>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    Producto
                                                                </th>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    Cantidad
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr
                                                                v-for="(
                                                                    item, index
                                                                ) in item.ingreso_detalles"
                                                            >
                                                                <td>
                                                                    {{
                                                                        index +
                                                                        1
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        item
                                                                            .producto
                                                                            .nombre
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        item.cantidad
                                                                    }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </v-table>
                                                </v-col>
                                            </v-row>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td>
                                            <ul class="flex-content">
                                                <li
                                                    class="flex-item"
                                                    data-label="Id:"
                                                >
                                                    {{ item.id }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Proveedor:"
                                                >
                                                    {{
                                                        item.proveedor
                                                            .razon_social
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Tipo de Ingreso:"
                                                >
                                                    {{
                                                        item.tipo_ingreso.nombre
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Precio:"
                                                >
                                                    {{ item.precio }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Nro. Factura:"
                                                >
                                                    {{ item.nro_factura }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Descripción:"
                                                >
                                                    {{ item.descripcion }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha de Ingreso:"
                                                >
                                                    {{ item.fecha_ingreso_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha de Registro"
                                                >
                                                    {{ item.fecha_registro_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Mas:"
                                                >
                                                    <v-btn
                                                        :icon="
                                                            item.mas
                                                                ? 'mdi-chevron-down'
                                                                : 'mdi-chevron-left'
                                                        "
                                                        @click="
                                                            item.mas = !item.mas
                                                        "
                                                    ></v-btn>
                                                </li>
                                                <template v-if="item.mas">
                                                    <li
                                                        class="flex-item"
                                                        data-label="Ingreso Productos:"
                                                    >
                                                        <v-table
                                                            class="border tabla_info"
                                                        >
                                                            <tbody>
                                                                <tr
                                                                    v-for="(
                                                                        item,
                                                                        index
                                                                    ) in item.ingreso_detalles"
                                                                >
                                                                    <td>
                                                                        N°:
                                                                        {{
                                                                            index +
                                                                            1
                                                                        }}
                                                                    </td>
                                                                    <td>
                                                                        Producto:
                                                                        {{
                                                                            item
                                                                                .producto
                                                                                .nombre
                                                                        }}
                                                                    </td>
                                                                    <td>
                                                                        Cantidad:
                                                                        {{
                                                                            item.cantidad
                                                                        }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </v-table>
                                                    </li>
                                                </template>
                                            </ul>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center pa-5"
                                                >
                                                    <v-btn
                                                        color="yellow-accent-2"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="pdf(item)"
                                                        icon="mdi-file-outline"
                                                    ></v-btn>
                                                    <v-btn
                                                        color="primary"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            verIngreso(item)
                                                        "
                                                        icon="mdi-eye-circle"
                                                    ></v-btn>
                                                    <!-- <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'ingresos.edit'
                                                            )
                                                        "
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarIngreso(item)
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'ingresos.destroy'
                                                            )
                                                        "
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarIngreso(
                                                                item
                                                            )
                                                        "
                                                        icon="mdi-trash-can"
                                                    ></v-btn> -->
                                                </v-col>
                                            </v-row>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </v-data-table-server>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
