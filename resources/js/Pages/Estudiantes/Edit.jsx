import { Head, useForm } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import Form from './Form';
import { valoresIniciales } from '../../Components/CamposEstudiante';

export default function Edit({ estudiante }) {
    const form = useForm({
        ...valoresIniciales,
        nombre: estudiante.nombre,
        apellido: estudiante.apellido,
        username: estudiante.username,
        email: estudiante.email,
        carrera: estudiante.carrera,
        semestre: estudiante.semestre,
    });

    const submit = (e) => {
        e.preventDefault();
        form.put(`/estudiantes/${estudiante.id}`, { onError: () => form.reset('password', 'password_confirmation') });
    };

    return (
        <AppLayout title={`Editar: ${estudiante.nombre} ${estudiante.apellido}`}>
            <Head title="Editar estudiante" />
            <Form form={form} onSubmit={submit} submitLabel="Actualizar" editando />
        </AppLayout>
    );
}
