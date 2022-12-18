const nombreInput = document.getElementById('nombre');
const apellidoInput = document.getElementById('apellido');
const fechaInicio = document.getElementById('fechaInicio');
const fechaFin = document.getElementById('fechaFin');

const tiposDatos = {
  TEXT: 'TEXT',
  DATE: 'DATE',
};

const filtros = {
  nombre: {
    valor: '',
    tipoDato: tiposDatos.TEXT
  },
  apellido: {
    valor: '',
    tipoDato: tiposDatos.TEXT
  },
  fechaInicio: {
    valor: new Date('2022-04-14'),
    tipoDato: tiposDatos.DATE,
  },
  fechaFin: {
    valor: new Date('2022-08-24'),
    tipoDato: tiposDatos.DATE,
  },
};

function actualizarFiltrar(filtro, valor) {
  filtros[filtro].valor = valor;
  filtrar();
}


function filtrarTexto(arreglo, columna, valor) {
  const nuevoArreglo = [];
  if (valor == '') {
      nuevoArreglo.length = 0;
  }

  const regexp = new RegExp(`.*${valor}.*`, 'i');
  for (const elemento of arreglo) {
      const valorElemento = elemento[columna];
      if (regexp.test(valorElemento))  nuevoArreglo.push(elemento);
  }
  return nuevoArreglo;
}

function filtrarFecha(arreglo, columna, valor) {
  const nuevoArreglo = [];
  if (valor == '') {
    nuevoArreglo.length = 0;
  }

  for (const elemento of arreglo) {
    const valorElemento = elemento[columna];
    if (valor.fechaInicio < valorElemento && valorElemento < valor.fechaFin) {
      nuevoArreglo.push(elemento);
    }
  }

  return nuevoArreglo;
}

function filtrarRangoFecha(arreglo, columna, valor) {
  const nuevoArreglo = [];

  for (const elemento of arreglo) {
    const valorElemento = elemento[columna];
    if (valor.fechaInicio < valorElemento && valorElemento < valor.fechaFin) {
      nuevoArreglo.push(elemento);
    }
  }

  return nuevoArreglo;
}

const arregloDummy = [
  {
    nombre: 'Foo',
    apellido: 'Foo',
    fecha: new Date('2022-07-12'),
  },
  {
    nombre: 'Bar',
    apellido: 'Bar',
    fecha: new Date('2022-05-14'),
  },
  {
    nombre: 'Otro',
    apellido: 'Otro',
    fecha: new Date('2021-08-22'),
  },
  {
    nombre: 'Something',
    apellido: 'Something',
    fecha: new Date('2020-02-15'),
  },
];

function filtrar() {
  const nuevoArray = filtrarFecha(
    filtrarTexto(arregloDummy, 'nombre', 'o'),
    'fecha',
    {
      fechaInicio: filtros.fechaInicio.valor,
      fechaFin: filtros.fechaFin.valor,
    }
  );

  return nuevoArray;
}

//console.log(filtrar());

//nombreInput.addEventListener('change', (e) => actualizarFiltrar('nombre', e.target.value));
//apellidoInput.addEventListener('change', (e) => actualizarFiltrar('apellido', e.target.value));