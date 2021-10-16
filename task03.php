<?php

# membuat class Animal
class Animal
{
  # property animals

  # method constructor - mengisi data awal
  # parameter: data hewan (array)

  public $animals = [];

  public function __construct($data)
  {
    $this->animals = $data;
  }

  # method index - menampilkan data animals
  public function index()
  {
    # gunakan foreach untuk menampilkan data animals (array)
    foreach ($this->animals as $row) {
      echo $row;
      echo "<br>";
    }
  }

  # method store - menambahkan hewan baru
  # parameter: hewan baru
  public function store($name)
  {
    # gunakan method array_push untuk menambahkan data baru
    array_push($this->animals, $name);
  }

  # method update - mengupdate hewan
  # parameter: index dan hewan baru
  public function update($index, $name)
  {
    // cara 1
    // foreach ($this->animals as $key => $animal) {
    //   if ($key == $index) {
    //     $this->animals[$key] = $name;
    //   }
    // }

    // cara 2
    $updateData = array($index => $name);
    $this->animals = array_replace($this->animals, $updateData);
  }

  # method delete - menghapus hewan
  # parameter: index
  public function destroy($index)
  {
    # gunakan method unset atau array_splice untuk menghapus data array
    array_splice($this->animals, $index, 1);
    // unset($this->animals[$index]);
  }
}

# membuat object
# kirimkan data hewan (array) ke constructor
$animal = new Animal(['Ayam', 'Ikan']);

echo "Index - Menampilkan seluruh hewan <br>";
$animal->index();
echo "<br>";

echo "Store - Menambahkan hewan baru <br>";
$animal->store('Burung');
$animal->index();
echo "<br>";

echo "Update - Mengupdate hewan <br>";
$animal->update(0, 'Kucing Anggora');
$animal->index();
echo "<br>";

echo "Destroy - Menghapus hewan <br>";
$animal->destroy(1);
$animal->index();
echo "<br>";
