import { Head, useForm } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import Form from './Form';

export default function Create() {
    const form = useForm({ nombre: '', apellido: '', email: '', carrera: '', semestre: '' });

    const submit = (e) => {
        e.preventDefault();
        form.post('/estudiantes');
    };

    return (
        <AppLayout title="Nuevo estudiante">
            <Head title="Nuevo estudiante" />
            <Form form={form} onSubmit={submit} submitLabel="Guardar" />
        </AppLayout>
    );
}
