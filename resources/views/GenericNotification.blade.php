<!DOCTYPE html>

<html>
<body>
  <div style="
  color: #3A3D51;
  margin-bottom:35px;
  ">

    <h2>Nouvelle notification</h2>
    <h2 style="margin-bottom:0;">
      {{ $infos['projectName'] }}
    </h2>
    <h3 style="margin-top:5px; margin-bottom:30px;"
      ><i>Le {!! $infos['date'] !!} à {!! $infos['time'] !!}</i>
    </h3>

    @foreach($infos['fields'] as $label => $value)
    <p><b>{{ $label }} : </b>{!! $value !!}</p>
    @endforeach

  </div>

  <div style="
  line-height:0.5em;
  font-size:0.7em;
  color: #125400;
  font-weight: bold;
  letter-spacing: 0.1em;
  font-style: italic;
  ">
    <p>kafoo . dev - Antoine Guillard</p>
    <p>ant.guillard@gmail.com</p>
    <p>+33 6 42 40 29 16</p>
  </div>

</body>
</html>
