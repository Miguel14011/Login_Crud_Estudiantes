import { Head, useForm } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import Form from './Form';
import { valoresIniciales } from '../../Components/CamposEstudiante';

export default function Create() {
    const form = useForm(valoresIniciales);

    const submit = (e) => {
        e.preventDefault();
        form.post('/estudiantes', { onError: () => form.reset('password', 'password_confirmation') });
    };

    return (
        <AppLayout title="Nuevo estudiante">
            <Head title="Nuevo estudiante" />
            <Form form={form} onSubmit={submit} submitLabel="Guardar" />
        </AppLayout>
    );
}
