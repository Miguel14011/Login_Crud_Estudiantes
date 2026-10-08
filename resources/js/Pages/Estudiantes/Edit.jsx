import { Head, useForm } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import Form from './Form';

export default function Edit({ estudiante }) {
    const form = useForm({
        nombre: estudiante.nombre,
        apellido: estudiante.apellido,
        email: estudiante.email,
        carrera: estudiante.carrera,
        semestre: estudiante.semestre,
    });

    const submit = (e) => {
        e.preventDefault();
        form.put(`/estudiantes/${estudiante.id}`);
    };

    return (
        <AppLayout title={`Editar: ${estudiante.nombre} ${estudiante.apellido}`}>
            <Head title="Editar estudiante" />
            <Form form={form} onSubmit={submit} submitLabel="Actualizar" />
        </AppLayout>
    );
}
