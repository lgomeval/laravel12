<?php

use Livewire\Volt\Component;
use App\Models\Especialista;

new class extends Component {
    public $especialista;

    public $nombres, $apellidos, $tipo_identificacion, $numero_identificacion, $genero, $fecha_de_nacimiento, $direccion_residencia, $telefono_contacto1, $telefono_contacto2, $email, $especialidad_medica, $registro_medico, $fecha_inicio_labor, $experiencia, $certificaciones, $estado;

    public function mount(Especialista $especialista)
    {
        $this->especialista = $especialista;

        foreach (['nombres', 'apellidos', 'tipo_identificacion', 'numero_identificacion', 'genero', 'fecha_de_nacimiento', 'direccion_residencia', 'telefono_contacto1', 'telefono_contacto2', 'email', 'especialidad_medica', 'registro_medico', 'fecha_inicio_labor', 'experiencia', 'certificaciones', 'estado'] as $prop) {
            if (property_exists($this, $prop)) {
                $this->$prop = $especialista->$prop;
            }
        }
    }

    public function actualizarEspecialista()
    {
        $this->especialista->update([
            'nombres' => $this->nombres,
            'apellidos' => $this->apellidos,
            'tipo_identificacion' => $this->tipo_identificacion,
            'numero_identificacion' => $this->numero_identificacion,
            'genero' => $this->genero,
            'fecha_de_nacimiento' => $this->fecha_de_nacimiento,
            'direccion_residencia' => $this->direccion_residencia,
            'telefono_contacto1' => $this->telefono_contacto1,
            'telefono_contacto2' => $this->telefono_contacto2,
            'email' => $this->email,
            'especialidad_medica' => $this->especialidad_medica,
            'registro_medico' => $this->registro_medico,
            'fecha_inicio_labor' => $this->fecha_inicio_labor,
            'experiencia' => $this->experiencia,
            'certificaciones' => $this->certificaciones,
            'estado' => $this->estado,
        ]);

        return redirect()->route('especialistas.index')->with('success', 'Especialista actualizado correctamente.');
    }
}; ?>
<div>
    <x-form-edit :model="$especialista" submitAction="actualizarEspecialista" :fields="[
        ['name' => 'nombres', 'label' => 'Nombres'],
        ['name' => 'apellidos', 'label' => 'Apellidos'],
        ['name' => 'tipo_identificacion', 'label' => 'Tipo de Identificación'],
        ['name' => 'numero_identificacion', 'label' => 'Número de Identificación'],
        ['name' => 'genero', 'label' => 'Género'],
        ['name' => 'fecha_de_nacimiento', 'label' => 'Fecha de Nacimiento', 'type' => 'date'],
        ['name' => 'direccion_residencia', 'label' => 'Dirección'],
        ['name' => 'telefono_contacto1', 'label' => 'Teléfono 1'],
        ['name' => 'telefono_contacto2', 'label' => 'Teléfono 2'],
        ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
        ['name' => 'especialidad_medica', 'label' => 'Especialidad Médica'],
        ['name' => 'registro_medico', 'label' => 'Registro Médico'],
        ['name' => 'fecha_inicio_labor', 'label' => 'Fecha de Inicio', 'type' => 'date'],
        ['name' => 'experiencia', 'label' => 'Experiencia'],
        ['name' => 'certificaciones', 'label' => 'Certificaciones'],
        ['name' => 'estado', 'label' => 'Estado', 'readonly' => true],
    ]" />
</div>
