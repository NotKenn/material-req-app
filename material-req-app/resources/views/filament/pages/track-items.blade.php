<div style="padding: 1rem;">

    <div style="margin-bottom: 1rem;">
        <h2 style="font-size: 20px; font-weight: bold;">
            {{ $record->kodeRequest }}
        </h2>

        <p style="margin-top: 4px;">
            Requester:
            {{ $record->requester->namaPT ?? '-' }}
        </p>
    </div>

    <div style="overflow-x: auto;">

        <table style="
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        ">

            <thead>

                <tr style="background: darkcyan;">

                    <th style="border:1px solid #ccc; padding:8px; text-align:left;">
                        Item
                    </th>

                    <th style="border:1px solid #ccc; padding:8px; text-align:center;">
                        Requested
                    </th>

                    <th style="border:1px solid #ccc; padding:8px; text-align:center;">
                        Remaining
                    </th>

                    <th style="border:1px solid #ccc; padding:8px; text-align:center;">
                        Status
                    </th>

                    <th style="border:1px solid #ccc; padding:8px; text-align:left;">
                        Related PO
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach ($items as $item)

                    @php

                        $remaining = $item->remainingQty ?? $item->Qty;

                        if ($remaining <= 0) {
                            $status = 'Closed';
                            $statusColor = '#16a34a';
                        } elseif ($remaining < $item->Qty) {
                            $status = 'Partial';
                            $statusColor = '#ca8a04';
                        } else {
                            $status = 'Open';
                            $statusColor = '#6b7280';
                        }

                    @endphp

                    <tr>

                        <td style="border:1px solid #ccc; padding:8px;">
                            {{ $item->itemName }}
                        </td>

                        <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                            {{ $item->Qty }} {{$item->satuan}}
                        </td>

                        <td style="border:1px solid #ccc; padding:8px; text-align:center;">
                            {{ $remaining }}
                        </td>

                        <td style="border:1px solid #ccc; padding:8px; text-align:center; color: {{ $statusColor }};">
                            {{ $status }}
                        </td>

                        <td style="border:1px solid #ccc; padding:8px;">

                            @php
                                $relatedPOs = $item->poItems
                                    ->pluck('poDetails')
                                    ->filter()
                                    ->unique('id');
                            @endphp

                            @forelse ($relatedPOs as $po)

                                <div style="margin-bottom:4px;">

                                    <a
                                        href="{{ route('po.preview.pdf', $po->id) }}"
                                        target="_blank"
                                        style="
                                            color:#2563eb;
                                            text-decoration:underline;
                                        "
                                    >
                                        {{ \App\Services\PoNumberFormatter::format($po) }}
                                    </a>

                                </div>

                            @empty

                                -

                            @endforelse

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>
