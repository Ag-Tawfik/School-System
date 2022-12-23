<button class="btn btn-success btn-sm btn-lg pull-right" wire:click="showformadd"
    type="button">{{ trans('Parent_trans.add_parent') }}</button><br><br>
<div class="table-responsive">
    <table id="datatable" class="table  table-hover table-sm table-bordered p-0" data-page-length="50"
        style="text-align: center">
        <thead>
            <tr class="table-success">
                <th>#</th>
                <th>{{ trans('Parent_trans.Email') }}</th>
                <th>{{ trans('Parent_trans.fatherName') }}</th>
                <th>{{ trans('Parent_trans.fatherNationalID') }}</th>
                <th>{{ trans('Parent_trans.fatherPassportID') }}</th>
                <th>{{ trans('Parent_trans.fatherPhoneNumber') }}</th>
                <th>{{ trans('Parent_trans.fatherJobTitle') }}</th>
                <th>{{ trans('Parent_trans.Processes') }}</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; ?>
            @foreach ($myParents as $myParent)
                <tr>
                    <?php $i++; ?>
                    <td>{{ $i }}</td>
                    <td>{{ $myParent->email }}</td>
                    <td>{{ $myParent->fatherName }}</td>
                    <td>{{ $myParent->fatherNationalID }}</td>
                    <td>{{ $myParent->fatherPassportID }}</td>
                    <td>{{ $myParent->fatherPhoneNumber }}</td>
                    <td>{{ $myParent->fatherJobTitle }}</td>
                    <td>
                        <button wire:click="edit({{ $myParent->id }})" title="{{ trans('Grades_trans.Edit') }}"
                            class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button>
                        <button type="button" class="btn btn-danger btn-sm" wire:click="delete({{ $myParent->id }})"
                            title="{{ trans('Grades_trans.Delete') }}"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @endforeach
    </table>
</div>
