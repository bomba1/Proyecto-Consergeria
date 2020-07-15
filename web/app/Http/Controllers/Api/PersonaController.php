<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PersonaStoreRequest;
use App\Http\Requests\PersonaUpdateRequest;
use App\Http\Resources\PersonaResource;
use App\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Freshwork\ChileanBundle\Rut;

/**
 * Class PersonaController
 * @package App\Http\Controllers\Api
 */
class PersonaController extends Controller
{
    /**
     * Display a listing of persons.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // SELECT * FROM personas
        $persona = Persona::orderBy('personas','ASC')->get();

        return response([
            'message' => 'Retrieved Successfully',
            'personas' => $persona,
        ]);
    }

    /**
     * Storing a validated person in the database.
     *
     * @param PersonaStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PersonaStoreRequest $request)
    {
        //Separamos los primeros 8 numeros y el digito verificador en variables distintas
        list($numero, $digitoVerificador) = explode('-', $request->rut);

        //Si el digito verificador es k minuscula, se reemplaza por k mayuscula y se ve si ya existe en la base de datos
        //Sino se devuelve el rut a su estado original
        if ($digitoVerificador == 'k') {
            $numero = $numero.'-K';
            if (DB::table('personas')->where('rut', $numero)->exists()) {
                return response([
                    'message' => 'Este Rut ya esta en uso',
                ], 412);
            }
        } else {
            $numero = $numero.'-'.$digitoVerificador;
        }

        $rut = $numero;
        $persona = Persona::create($request->all());
        $persona->rut = $rut;
        $persona->save();

        return response([
            'message' => 'Created Successfully',
            'persona' => new PersonaResource($persona),
        ],201);
    }

    /**
     * Display the specified person.
     *
     * @param  \App\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function show(Persona $persona)
    {
        return response([
            'message' => 'Retrieved Successfully',
            'persona' => new PersonaResource($persona),
        ],200);
    }

    /**
     * Update a validated person in the database.
     *
     * @param PersonaUpdateRequest $request
     * @param Persona $persona
     * @return \Illuminate\Http\Response
     */
    public function update(PersonaUpdateRequest $request, Persona $persona)
    {
        // Validacion adicional para que el unique del correo no afecte a si mismo.
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                Rule::unique('personas')->ignore($persona),
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Rut ya en uso',
                'error' => $validator->errors(),
            ], 412);
        }

        // Update
        $persona->fill($request->all());

        $persona->save();

        return response([
            'message' => 'Updated Successfully',
        ], 202);
    }

    /**
     * Remove the specified person from storage.
     *
     * @param  \App\Persona  $persona
     * @return \Illuminate\Http\Response
     */
    public function destroy(Persona $persona)
    {
        $persona->delete();
        return response([
            'message' => 'Deleted',
        ],202);
    }
}
