<?php

    enum TaskStatus : string {
        case PENDIENTE = "pendiente";
        case ACABADA = "acabada";
        case EMPEZADA = "empezada";
    }
    enum TaskTipe : string {
        case REUNION = "reunion";
        case REVISION = "revision";
        case DESARROLLO = "desarrollo";
        case FORMACION = "formacion";
    }
?>