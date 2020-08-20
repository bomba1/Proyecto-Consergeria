package com.pdbp.android_app

import com.pdbp.android_app.data.*
import okhttp3.OkHttpClient
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import retrofit2.http.*

/**
 * The interface for routes
 */
interface ApiRestEndPoints {

    //Route to get all personas
    @GET("persona")
    suspend fun findPersonas() : Personas

    //Route to get all propiedades
    @GET("propiedad")
    suspend fun findPropiedades() : Propiedades

    //Route to get all visitas
    @GET("registro")
    suspend fun findVisitas() : Visitas

    //Route to store a visita
    @POST("registro")
    @FormUrlEncoded
    suspend fun registroVisitas(@Field("fecha") fecha: String ,
                                @Field("parentesco") parentesco: String,
                                @Field("empresa_reparto") empresa_reparto: String,
                                @Field("persona_rut") persona_rut: String,
                                @Field("propiedad_numero") propiedad_numero: String,
                                @Header("Authorization") token: String) : RegistroResponse

    //Route to store a visita
    @POST("persona")
    @FormUrlEncoded
    suspend fun registroPersona(@Field("rut") rut: String ,
                                @Field("nombre") nombre: String,
                                @Field("telefono") telefono: String,
                                @Field("email") email: String,
                                @Header("Authorization") token: String) : PersonaResponse

    //Route to login to the server
    @POST("login")
    @FormUrlEncoded
    suspend fun login(@Field("email") email: String ,
                      @Field("password") password: String) : LoginResponse


}

//Here we obtain de url from the server, to connect to web
val retrofit = Retrofit.Builder()
    .baseUrl(" http://192.168.1.85:8000/api/")
    .client(OkHttpClient())
    .addConverterFactory(GsonConverterFactory.create())
    .build()

// Here we create the connection
val apiRestEndPoints: ApiRestEndPoints = retrofit.create(ApiRestEndPoints::class.java)

