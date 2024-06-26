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
        url: route("ingresos.index"),
        name_url: "ingresos.index",
    },
    {
        title: "Ver",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";
import { useMenu } from "@/composables/useMenu";
import { useIngresos } from "@/composables/ingresos/useIngresos";
import Formulario from "./parcials/Formulario.vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();

const { setLoading } = useApp();
const { oIngreso, setIngreso } = useIngresos();

const props = defineProps({
    ingreso: {
        type: Object,
    },
});
setIngreso(props.ingreso, true, true);

onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Ingresos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>

        <v-row class="mt-0">
            <v-col cols="12" sm="12" md="12">
                <v-card>
                    <v-card-text>
                        <h4 class="text-h5 font-weight-bold mb-3">
                            Información Ingreso Económico
                        </h4>
                        <p class="mb-2">
                            <strong>Categoría: </strong>
                            {{ oIngreso.categoria?.nombre }}
                        </p>
                        <p class="mb-2">
                            <strong>Fecha: </strong>
                            {{ oIngreso.fecha_t }}
                        </p>

                        <v-table class="border">
                            <thead>
                                <tr>
                                    <th class="text-center">N°</th>
                                    <th class="">Concepto</th>
                                    <th class="">Descripción</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(
                                        item_detalle, index
                                    ) in oIngreso.ingreso_detalles"
                                >
                                    <td>
                                        {{ index + 1 }}
                                    </td>
                                    <td>
                                        {{ item_detalle.concepto?.nombre }}
                                    </td>
                                    <td>
                                        {{ item_detalle.descripcion }}
                                    </td>
                                    <td class="text-center">
                                        {{ item_detalle.cantidad }}
                                    </td>
                                    <td class="text-center">
                                        {{ item_detalle.monto_txt }}
                                    </td>
                                </tr>
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th
                                        colspan="3"
                                        class="text-right font-weight-bold"
                                    >
                                        TOTAL
                                    </th>
                                    <th class="text-center font-weight-bold">
                                        {{ oIngreso.total_c }}
                                    </th>
                                    <th class="text-center font-weight-bold">
                                        {{ oIngreso.total_m_txt }}
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </v-table>

                        <v-btn
                            prepend-icon="mdi-arrow-left"
                            class="mr-2 mt-3"
                            @click="cambiarUrl(route('ingresos.index'))"
                        >
                            Volver</v-btn
                        >
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
