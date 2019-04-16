
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using Nest;

using KafkaNet;
using KafkaNet.Model;

namespace kafkaConsumer_elastic
{
    public class Logs
    {

        public int id { get; set; }
        public DateTime @timestamp { get; set; }
        public string log_level { get; set; }
        public string log_server_city_name { get; set; }
        public string log_detail { get; set; }

    }

    class Program
    {
        static void Main(string[] args)
        {
            int i = 0;

            var connectionSettings = new ConnectionSettings(new Uri("http://localhost:9200"));
           
            var elasticClient = new ElasticClient(connectionSettings);

            var createIndexResponse = elasticClient.CreateIndex("logs", c => c
             .Mappings(ms => ms
             .Map<Logs>(m => m.AutoMap())
              )
              );

            //var createIndexResponse = elasticClient.CreateIndex("logs", c => c
            // .Mappings(ms => ms
            //.Map<Logs>(m => m
            //    .Properties(ps => ps
            //         .Number(s => s
            //            .Name(n => n.id)
            //    )
            //)
            //)
            //)
            //);

            var options = new KafkaOptions(new Uri("http://localhost:9092"));
            var router = new BrokerRouter(options);
            var consumer = new Consumer(new ConsumerOptions("Logs", router));

            foreach (var message in consumer.Consume())
            {
                if (message != null)
                {
                    try
                    { 
                    i++;

                    Console.WriteLine("PartitionId: {0} Offset: {1} Message: {2}",
                        message.Meta.PartitionId,
                        message.Meta.Offset,
                        Encoding.UTF8.GetString(message.Value));

                    string result = System.Text.Encoding.UTF8.GetString(message.Value);
                    string[] datas = result.Split('\t');

                    //DateTime currDate = DateTime.ParseExact(datas[0], "yyyy-MM-dd HH:mm:ss.fff", System.Globalization.CultureInfo.InvariantCulture);
                    DateTime currDate = DateTime.Parse(datas[0]);


                    var curr = new Logs { id = i, @timestamp = currDate, log_level = datas[1], log_server_city_name = datas[2], log_detail = datas[3] };
                        if (curr != null)
                        {
                            //var indexResponse = elasticClient.Index(curr, descriptor => descriptor.Index("logs"));
                            var task = elasticClient.IndexAsync(curr, d => d.Index("logs"));
                            //var r = elasticClient.Refresh("logs");


                            //var result2 = elasticClient.GetIndexAsync(null, c => c
                            //                 .AllIndices()
                            //         );

                            //System.Threading.Thread.Sleep(1000);
                            //Console.WriteLine("--->" + curr.id);
                        }
                    }
                    catch (Exception exception)
                    {
                        Console.WriteLine(exception);
                    }

                }

            }
        }




    }
}
