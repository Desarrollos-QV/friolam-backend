<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <table>
    <thead>
      <tr>
        <td><b>ID</b></td>
        <td><b>Fecha</b></td>
        <td><b>cliente</b></td>
        <td><b>Sucursal</b></td>
        <td><b>Lugar de carga</b></td>
        <td><b>Lugar de descargue</b></td>
        <td><b>material</b></td>
        <td><b>cantidad</b></td>
        <td><b>Conductor</b></td>
        <td><b>PLACA</b></td>
        <td><b>factura</b></td>
        <td><b>costos</b></td>
        <td><b>observaciones</b></td> 
      </tr>
    </thead>
    <tbody> 
      @foreach($data as $row)  
      <tr>
        <td>#{{ $row['id'] }}</td>
        <td>{{ $row['date'] }}</td>
        <td>{{ $row['client'] }}</td>
        <td>{{ $row['sucursal'] }}</td>
        <td>{{ $row['cargue'] }}</td>
        <td>{{ $row['descargue'] }}</td>
        <td>{{ $row['material'] }}</td>
        <td>{{ $row['cantidad_c'] }}</td> 
        <td>{{ $row['dboy'] }}</td>
        <td>{{ $row['placa'] }}</td>
        <td>#{{ $row['factura'] }}</td>
        <td>{{ $row['costos'] }}</td>
        <td>{{ $row['obs'] }}</td> 
      </tr>
      @endforeach   
    </tbody>
  </table>
</body>
</html>