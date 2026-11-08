import { Head, useForm } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import Form from './Form';
import { valoresIniciales } from '../../Components/CamposEstudiante';

export default function Edit({ estudiante, facultades }) {
    const form = useForm({
        ...valoresIniciales,
        nombre: estudiante.nombre,
        apellido: estudiante.apellido,
        cedula: estudiante.cedula ?? '',
        username: estudiante.username,
        email: estudiante.email,
        // Se preselecciona la facultad: así el dropdown de carreras se carga al abrir la página
        facultad_id: estudiante.carrera?.facultad_id ?? '',
        carrera_id: estudiante.carrera_id ?? '',
        semestre: estudiante.semestre,
    });

    const submit = (e) => {
        e.preventDefault();
        form.put(`/estudiantes/${estudiante.id}`, { onError: () => form.reset('password', 'password_confirmation') });
    };

    return (
        <AppLayout title={`Editar: ${estudiante.nombre} ${estudiante.apellido}`}>
            <Head title="Editar estudiante" />
            <Form form={form} facultades={facultades} onSubmit={submit} submitLabel="Actualizar" editando />
        </AppLayout>
    );
}
