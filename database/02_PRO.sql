CREATE OR REPLACE FUNCTION ins_eess(nn INT,code VARCHAR(10),es VARCHAR(70))
RETURNS INT AS $$
DECLARE
    captura INT;
    error INT := 0;
BEGIN
    SELECT id INTO captura FROM oodd WHERE numera = nn;
    IF NOT FOUND THEN error := 1;
    ELSE
        INSERT INTO eess (oodd_id, codigo, eess) VALUES (captura, code, es);
    END IF;
    RETURN error;
END;
$$ LANGUAGE plpgsql;

-- SELECT INS_EESS('nn_value', 'code_value', 'eess_value'); ------------------------------

CREATE OR REPLACE FUNCTION etapa1_alimentar_crearhoja_oodd(redx INT)
RETURNS INT AS $$
DECLARE
    error INT := 0;         -- Variable para manejar errores
    totx INT := 0;          -- Total de registros
    inix INT := 0;          -- Variable para el ID inicial de `eess`
    tmpx INT := 0;          -- Variable para el ID temporal
    filaxx INT := 0;        -- Variable para contar el número de registros en `pofi`
    i INT := 0;             -- Variable para la iteración interna
BEGIN
    SELECT COUNT(id) INTO filaxx FROM pofi;
    SELECT COUNT(id) INTO totx FROM eess WHERE oodd_id = redx;
    SELECT MIN(id) INTO inix FROM eess WHERE oodd_id = redx;--1 

    -- Inserción en `CABEZA` con el valor 1 para `actioodd_id`
    INSERT INTO cabeza (oodd_id, eess_id, actioodd_id, imagen) VALUES (redx, inix, 1, 1)
    RETURNING id INTO tmpx;
    FOR i IN 1..filaxx LOOP
        INSERT INTO detalle (cabeza_id, pofi_id) VALUES (tmpx, i);
    END LOOP;

    -- Inserción en `CABEZA` con el valor 2 para `actioodd_id`
    INSERT INTO cabeza (oodd_id, eess_id, actioodd_id, imagen) VALUES (redx, inix, 2, 1)
    RETURNING id INTO tmpx;
    FOR i IN 1..filaxx LOOP
        INSERT INTO detalle (cabeza_id, pofi_id) VALUES (tmpx, i);
    END LOOP;

    -- Inserción en `CABEZA` con el valor 3 para `actioodd_id`
    INSERT INTO cabeza (oodd_id, eess_id, actioodd_id, imagen) VALUES (redx, inix, 3, 1)
    RETURNING id INTO tmpx;
    FOR i IN 1..filaxx LOOP
        INSERT INTO detalle (cabeza_id, pofi_id)
        VALUES (tmpx, i);
    END LOOP;

    -- Iterar sobre los demás `eess` que son cabecera (inix se incrementa)
    totx := totx + inix -1; 
    inix := inix + 1; --2
    
    FOR i IN inix..totx LOOP 
        -- Inserción en `NOCABEZA` con el valor 1 para `actioodd_id`
        INSERT INTO cabeza (oodd_id, eess_id, actioodd_id, imagen) VALUES (redx, i, 1, 1)
        RETURNING id INTO tmpx;
        FOR j IN 1..filaxx LOOP
            INSERT INTO detalle (cabeza_id, pofi_id) VALUES (tmpx, j);
        END LOOP;

        -- Inserción en `NOCABEZA` con el valor 2 para `actioodd_id`
        INSERT INTO cabeza (oodd_id, eess_id, actioodd_id, imagen) VALUES (redx, i, 2, 1)
        RETURNING id INTO tmpx;
        FOR j IN 1..filaxx LOOP
            INSERT INTO detalle (cabeza_id, pofi_id) VALUES (tmpx, j);
        END LOOP;
    END LOOP;
    RETURN 0;

EXCEPTION
    WHEN OTHERS THEN RETURN 1;
END;
$$ LANGUAGE plpgsql;

-- SELECT etapa1_alimentar_crearhoja_oodd(1);
-- SELECT etapa1_alimentar_crearhoja_oodd(2);
-- SELECT etapa1_alimentar_crearhoja_oodd(3);

CREATE OR REPLACE FUNCTION etapa1_alimentar_restaurarhoja_oodd(cabezax INT)
RETURNS INT AS $$
DECLARE
    error INT := 0;         -- Variable para manejar errores
    filaxx INT := 0;        -- Variable para contar el número de registros en `pofi`
    i INT := 0;             -- Variable para la iteración interna
BEGIN
    DELETE FROM detalle WHERE cabeza_id = cabezax;
    SELECT COUNT(id) INTO filaxx FROM pofi;
    
    FOR i IN 1..filaxx LOOP
        INSERT INTO detalle (cabeza_id, pofi_id) VALUES (cabezax, i);
    END LOOP;
    RETURN 0;

EXCEPTION
    WHEN OTHERS THEN RETURN 1;
END;
$$ LANGUAGE plpgsql;

-- SELECT etapa1_alimentar_restaurarhoja_oodd(1);

CREATE OR REPLACE FUNCTION etapa1_alimentar_crearhoja_oocc_nueva_activ(numerax INT,fondox INT,codex VARCHAR(16),actx VARCHAR(200),priox INT) 
RETURNS INT AS $$
DECLARE
    error INT := 0;         -- Variable para manejar errores
    idocx INT := 0;         -- Variable para almacenar el id de `oocc`
    varx INT := 0;          -- Variable para almacenar el id de `ACTIOOCC`
    tmpx INT := 0;          -- Variable para almacenar el id de `CABEZA`
    filaxx INT := 0;        -- Variable para contar el número de registros en `pofi`
    i INT := 0;             -- Variable para la iteración interna
BEGIN
    SELECT COUNT(id) INTO filaxx FROM pofi;
    SELECT id INTO idocx FROM oocc WHERE numera = numerax LIMIT 1;

    INSERT INTO actioocc (oocc_id,fondo,codigo,actividad,prioridad) VALUES (idocx,fondox,codex,actx,priox)
    RETURNING id INTO varx;
   
    INSERT INTO cabeza (oocc_id, actioocc_id, imagen) VALUES (idocx, varx, 1)
    RETURNING id INTO tmpx;
    
    FOR i IN 1..filaxx LOOP
        INSERT INTO detalle (cabeza_id, pofi_id) 
        VALUES (tmpx, i);
    END LOOP;
    
    RETURN 0;

EXCEPTION
    WHEN OTHERS THEN RETURN 1;
END;
$$ LANGUAGE plpgsql;

-- SELECT etapa1_alimentar_crearhoja_oocc_nueva_activ(11,1,'11xxxxxxxxxx123','ActividadPorMeta1',1) 


--RESTAURAR:
CREATE OR REPLACE FUNCTION etapa1_alimentar_restaurarhoja_oodd_valores(cabezax INT)
RETURNS INT AS $$
DECLARE
    error INT := 0;
    pofi_row RECORD;
BEGIN
    FOR pofi_row IN SELECT id FROM pofi LOOP
        -- Intentamos insertar solo si no existe ya el registro
        INSERT INTO detalle (cabeza_id, pofi_id)
        SELECT cabezax, pofi_row.id
        WHERE NOT EXISTS (
            SELECT 1 FROM detalle WHERE cabeza_id = cabezax AND pofi_id = pofi_row.id
        );
    END LOOP;

    RETURN 0;

EXCEPTION
    WHEN OTHERS THEN
        RETURN 1;
END;
$$ LANGUAGE plpgsql;
