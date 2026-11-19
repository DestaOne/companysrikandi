<x-ui.input name="title" label="Judul" :value="old('title', $gallery->title ?? '')" required />
<x-ui.textarea name="description" label="Deskripsi" :value="old('description', $gallery->description ?? '')" />
<x-ui.file-upload name="image" :label="isset($gallery) ? 'Gambar (kosongkan jika tidak diganti)' : 'Gambar'"
                  :current="isset($gallery) ? asset('storage/'.$gallery->image) : null" />
